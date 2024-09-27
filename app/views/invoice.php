<?php
// invoice.php - Fatura Sayfası

// Fatura ile ilgili gerekli verileri alalım
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    echo "Geçersiz fatura!";
    exit;
}

// Veritabanından sipariş ve müşteri bilgilerini alalım
$order = getOrderById($orderId);
$customer = getUserById($order['user_id']);
$products = getOrderProducts($orderId);

// Eğer sipariş bulunamazsa
if (!$order) {
    echo "Fatura bulunamadı!";
    exit;
}

// Fatura başlığı ve bilgileri
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura - <?php echo $order['order_number']; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <div class="invoice-container">
        <h1>Fatura</h1>
        <div class="invoice-header">
            <div class="invoice-details">
                <h2>Sipariş Numarası: <?php echo $order['order_number']; ?></h2>
                <p>Tarih: <?php echo date('d-m-Y', strtotime($order['created_at'])); ?></p>
            </div>
            <div class="customer-details">
                <h3>Müşteri Bilgileri</h3>
                <p>Ad Soyad: <?php echo $customer['name']; ?></p>
                <p>E-posta: <?php echo $customer['email']; ?></p>
                <p>Adres: <?php echo $customer['address']; ?></p>
            </div>
        </div>

        <div class="invoice-products">
            <h3>Sipariş Edilen Ürünler</h3>
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Adet</th>
                        <th>Fiyat</th>
                        <th>Toplam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td><?php echo number_format($product['price'], 2); ?> TL</td>
                            <td><?php echo number_format($product['price'] * $product['quantity'], 2); ?> TL</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="invoice-total">
            <h3>Toplam Tutar: <?php echo number_format($order['total_price'], 2); ?> TL</h3>
        </div>

        <div class="invoice-actions">
            <button onclick="window.print()">Yazdır</button>
            <button onclick="sendInvoiceAsPDF('<?php echo $order['order_number']; ?>')">PDF Olarak İndir</button>
            <button onclick="sendInvoiceByEmail('<?php echo $customer['email']; ?>')">E-posta Gönder</button>
        </div>
    </div>

    <script>
        function sendInvoiceAsPDF(orderNumber) {
            // PDF indirme işlemi
            window.location.href = 'generate_pdf.php?order_number=' + orderNumber;
        }

        function sendInvoiceByEmail(email) {
            // E-posta gönderme işlemi
            window.location.href = 'send_invoice_email.php?email=' + email;
        }
    </script>
</body>
</html>
