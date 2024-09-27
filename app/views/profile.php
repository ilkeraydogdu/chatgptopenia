<?php
// Kullanıcı oturum kontrolü
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Veritabanı bağlantısı
require_once '../app/config/database.php';
require_once '../app/models/User.php';
require_once '../app/models/Order.php';

// Kullanıcı bilgilerini alma
$userModel = new User();
$user = $userModel->getUserById($_SESSION['user_id']);

// Kullanıcının sipariş geçmişini alma
$orderModel = new Order();
$orders = $orderModel->getOrdersByUserId($_SESSION['user_id']);

// Profil sayfası HTML başlığı
require_once 'header.php';
?>

<div class="container">
    <h1>Profilim</h1>
    
    <!-- Kullanıcı bilgileri -->
    <h2>Kişisel Bilgiler</h2>
    <p>İsim: <?php echo htmlspecialchars($user['name']); ?></p>
    <p>E-posta: <?php echo htmlspecialchars($user['email']); ?></p>
    
    <!-- Profil bilgilerini düzenleme butonu -->
    <a href="profile_edit.php" class="btn btn-primary">Bilgilerimi Düzenle</a>

    <!-- Kullanıcının sipariş geçmişi -->
    <h2>Sipariş Geçmişim</h2>
    <?php if (!empty($orders)) : ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Sipariş No</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th>Toplam Tutar</th>
                    <th>Detaylar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_amount']); ?> TL</td>
                        <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info">Detaylar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Sipariş geçmişiniz bulunmamaktadır.</p>
    <?php endif; ?>
    
    <!-- Favori ürünler -->
    <h2>Favori Ürünlerim</h2>
    <p>Bu bölüm yakında eklenecektir.</p>
</div>

<?php require_once 'footer.php'; ?>
