<?php
// Ürün modelini dahil et
require_once 'app/models/Product.php';

// Ürün ID'sini GET parametresinden al
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = new Product();
    $productDetails = $product->getProductById($product_id);

    if (!$productDetails) {
        echo "Ürün bulunamadı.";
        exit();
    }
} else {
    echo "Ürün ID'si belirtilmedi.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $productDetails['product_name']; ?> - Ürün Detayları</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <!-- Ürün Detayları -->
    <div class="product-details">
        <h1><?php echo $productDetails['product_name']; ?></h1>
        <img src="public/assets/images/<?php echo $productDetails['image']; ?>" alt="<?php echo $productDetails['product_name']; ?>">
        <p class="price">Fiyat: <?php echo $productDetails['price']; ?> TL</p>
        <p class="description"><?php echo $productDetails['description']; ?></p>
        
        <!-- Ürün Stok Durumu -->
        <p class="stock-status">
            Stok Durumu: 
            <?php echo ($productDetails['stock'] > 0) ? 'Stokta Var' : 'Stokta Yok'; ?>
        </p>

        <!-- Sepete Ekleme Formu -->
        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $productDetails['id']; ?>">
            <label for="quantity">Adet:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $productDetails['stock']; ?>">
            <button type="submit">Sepete Ekle</button>
        </form>

        <!-- Ürün Notu Ekleme (İsteğe Bağlı) -->
        <form action="cart.php" method="POST">
            <label for="note">Bu ürün için not ekle:</label>
            <textarea name="note" id="note" rows="3"></textarea>
            <button type="submit">Not Ekle</button>
        </form>
    </div>

    <!-- Yorumlar Bölümü -->
    <div class="product-reviews">
        <h2>Yorumlar</h2>
        <?php
        // Ürün yorumlarını çekme işlemi
        $reviews = $product->getReviewsByProductId($product_id);
        if ($reviews) {
            foreach ($reviews as $review) {
                echo "<p><strong>" . htmlspecialchars($review['username']) . ":</strong> " . htmlspecialchars($review['comment']) . "</p>";
            }
        } else {
            echo "<p>Henüz yorum yapılmamış.</p>";
        }
        ?>
    </div>
</body>
</html>
