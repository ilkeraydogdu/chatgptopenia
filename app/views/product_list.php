<?php
// Veritabanı bağlantısını ve gerekli modelleri dahil edin
include 'header.php';

// Ürünleri veritabanından çekmek için model kullanın
$products = $productModel->getAllProducts();

// Filtreleme veya arama yapıldıysa, ilgili sorguları uygulayın
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

if ($category) {
    $products = $productModel->getProductsByCategory($category);
} elseif ($search) {
    $products = $productModel->searchProducts($search);
}
?>

<div class="container">
    <h1>Ürün Listesi</h1>

    <!-- Arama Formu -->
    <form method="GET" action="product_list.php">
        <input type="text" name="search" placeholder="Ürün ara..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Ara</button>
    </form>

    <!-- Filtreleme -->
    <div class="filter">
        <a href="product_list.php">Tüm Ürünler</a>
        <a href="product_list.php?category=elektronik">Elektronik</a>
        <a href="product_list.php?category=mobilya">Mobilya</a>
        <a href="product_list.php?category=giyim">Giyim</a>
    </div>

    <!-- Ürün Listesi -->
    <div class="product-list">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="<?= $product['image_url']; ?>" alt="<?= $product['name']; ?>">
                    <h2><?= htmlspecialchars($product['name']); ?></h2>
                    <p>Fiyat: <?= $product['price']; ?> TL</p>
                    <a href="product_details.php?id=<?= $product['id']; ?>" class="btn">Detaylar</a>
                    <a href="add_to_cart.php?id=<?= $product['id']; ?>" class="btn">Sepete Ekle</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Ürün bulunamadı.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
