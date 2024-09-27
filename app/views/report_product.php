<?php
// report_product.php

// Giriş yapmış kullanıcının admin olup olmadığını kontrol ediyoruz
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Veritabanı bağlantısı
require_once '../app/config/database.php';

// Ürün raporlarını çekmek için sorgu
$sql = "SELECT products.id, products.name, products.category, SUM(orders.quantity) AS total_quantity_sold, 
        COUNT(orders.id) AS total_orders, AVG(orders.rating) AS average_rating
        FROM products
        LEFT JOIN orders ON products.id = orders.product_id
        GROUP BY products.id, products.name, products.category";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Raporları</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ürün Bazlı Raporlar</h1>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Ürün ID</th>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Toplam Satılan Adet</th>
                    <th>Toplam Sipariş Sayısı</th>
                    <th>Ortalama Değerlendirme</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Sonuçları döngü ile listeleme
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['total_quantity_sold'] . "</td>";
                        echo "<td>" . $row['total_orders'] . "</td>";
                        echo "<td>" . number_format($row['average_rating'], 1) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Henüz sipariş bulunmamaktadır.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
