<?php
// Gerekli dosyalar dahil edilir
include_once 'header.php'; // Sayfanın başlık kısmı (navbar vb.)
include_once 'config/database.php'; // Veritabanı bağlantısı

// Kullanıcı oturumu kontrolü
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kullanıcı ID'si alınır
$user_id = $_SESSION['user_id'];

// Onaylanacak işlemin detayları, GET parametresi ile alınır (örneğin, sipariş ID'si)
$confirm_id = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($confirm_id && $action) {
    // Veritabanından gerekli bilgileri çek (örneğin, sipariş onayı veya hesap onayı)
    $query = "SELECT * FROM orders WHERE order_id = :order_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':order_id', $confirm_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // İşlem onaylandığında yapılacak işlemler (örneğin, siparişin onaylanması)
        if ($action == 'approve') {
            $updateQuery = "UPDATE orders SET status = 'confirmed' WHERE order_id = :order_id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':order_id', $confirm_id);
            $updateStmt->execute();

            echo "<div class='alert alert-success'>İşleminiz başarıyla onaylandı.</div>";
        }
        // İşlem iptal edildiyse
        elseif ($action == 'cancel') {
            $updateQuery = "UPDATE orders SET status = 'canceled' WHERE order_id = :order_id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':order_id', $confirm_id);
            $updateStmt->execute();

            echo "<div class='alert alert-warning'>İşleminiz iptal edildi.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Geçersiz işlem veya yetkisiz erişim.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Onaylanacak işlem bulunamadı.</div>";
}

include_once 'footer.php'; // Sayfanın footer kısmı
?>
