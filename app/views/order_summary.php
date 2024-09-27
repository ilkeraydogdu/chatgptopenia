<?php
// Gerekli dosyaları ve session kontrolü
require_once 'header.php'; 
require_once 'footer.php'; 

// Kullanıcı login değilse yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Sipariş ID ve kullanıcı bilgileri
$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Sipariş bilgilerini veritabanından çekme
$orderDetails = $orderModel->getOrderDetails($order_id, $user_id);
if (!$orderDetails) {
    echo "Sipariş bilgileri bulunamadı.";
    exit();
}

// Sipariş özetini ekranda gösterme
?>
<div class="order-summary">
    <h2>Sipariş Özeti</h2>
    
    <p><strong>Sipariş Numarası:</strong> <?php echo $orderDetails['order_number']; ?></p>
    <p><strong>Sipariş Tarihi:</strong> <?php echo $orderDetails['order_date']; ?></p>
    <p><strong>Kullanıcı Adı:</strong> <?php echo $orderDetails['user_name']; ?></p>
    <p><strong>Toplam Tutar:</strong> <?php echo $orderDetails['total_price']; ?> TL</p>

    <h3>Ürünler</h3>
    <ul>
        <?php foreach ($orderDetails['products'] as $product): ?>
            <li>
                <strong><?php echo $product['product_name']; ?></strong> 
                - <?php echo $product['quantity']; ?> adet 
                - <?php echo $product['price']; ?> TL
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Notlar</h3>
    <p><?php echo $orderDetails['order_notes'] ? $orderDetails['order_notes'] : "Sipariş için not eklenmemiş."; ?></p>

    <h3>Gönderim Bilgileri</h3>
    <p><strong>Adres:</strong> <?php echo $orderDetails['shipping_address']; ?></p>

    <!-- PDF olarak indir butonu -->
    <form method="post" action="generate_pdf.php">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <button type="submit" class="btn">Sipariş Özeti PDF İndir</button>
    </form>

    <!-- Mail gönderme butonları -->
    <form method="post" action="send_email.php">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <button type="submit" class="btn">Siparişi Mail Olarak Gönder</button>
    </form>
    
    <form method="post" action="send_to_friend.php">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <input type="email" name="friend_email" placeholder="Arkadaşınızın Email Adresi">
        <button type="submit" class="btn">Arkadaşa Mail Gönder</button>
    </form>
</div>

<?php
require_once 'footer.php';
?>
