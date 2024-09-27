<?php
// payment_methods.php

// Başlangıçta oturumun başlatıldığından emin olun
session_start();

// Veritabanı bağlantısı
require_once '../app/config/database.php';

// Kullanıcının admin olup olmadığını kontrol et
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    die('Bu sayfaya erişim yetkiniz yok.');
}

// Ödeme yöntemlerini veritabanından çekme
function getPaymentMethods($db) {
    $query = "SELECT * FROM payment_methods";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ödeme yöntemi ekleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_method'])) {
    $method_name = $_POST['method_name'];
    $status = $_POST['status'];

    $query = "INSERT INTO payment_methods (method_name, status) VALUES (:method_name, :status)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':method_name', $method_name);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    header('Location: payment_methods.php');
}

// Ödeme yöntemi silme
if (isset($_GET['delete_method'])) {
    $method_id = $_GET['delete_method'];

    $query = "DELETE FROM payment_methods WHERE id = :method_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':method_id', $method_id);
    $stmt->execute();
    header('Location: payment_methods.php');
}

// Veritabanından mevcut ödeme yöntemlerini al
$payment_methods = getPaymentMethods($db);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Yöntemleri Yönetimi</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ödeme Yöntemleri Yönetimi</h1>

        <form action="payment_methods.php" method="POST">
            <label for="method_name">Ödeme Yöntemi Adı:</label>
            <input type="text" name="method_name" required>
            
            <label for="status">Durum:</label>
            <select name="status" required>
                <option value="active">Aktif</option>
                <option value="inactive">Pasif</option>
            </select>
            
            <button type="submit" name="add_method">Ödeme Yöntemi Ekle</button>
        </form>

        <hr>

        <h2>Mevcut Ödeme Yöntemleri</h2>
        <table>
            <thead>
                <tr>
                    <th>Ödeme Yöntemi</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payment_methods as $method): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($method['method_name']); ?></td>
                        <td><?php echo $method['status'] == 'active' ? 'Aktif' : 'Pasif'; ?></td>
                        <td>
                            <a href="payment_methods.php?delete_method=<?php echo $method['id']; ?>" 
                               onclick="return confirm('Bu ödeme yöntemini silmek istediğinize emin misiniz?')">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
