<?php
// Ana sayfa için header yükleniyor
include 'header.php';
?>

<div class="container">
    <h1>Welcome to Our Online Store</h1>
    <p>Find the best products at affordable prices.</p>
    
    <!-- Öne çıkan ürünler bölümü -->
    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-list">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-item">
                    <img src="/public/assets/images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                    <h3><?= $product['name'] ?></h3>
                    <p><?= $product['description'] ?></p>
                    <p>Price: $<?= $product['price'] ?></p>
                    <a href="/product_details.php?id=<?= $product['id'] ?>" class="btn">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Yeni ürünler bölümü -->
    <section class="new-products">
        <h2>New Arrivals</h2>
        <div class="product-list">
            <?php foreach ($newProducts as $product): ?>
                <div class="product-item">
                    <img src="/public/assets/images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                    <h3><?= $product['name'] ?></h3>
                    <p><?= $product['description'] ?></p>
                    <p>Price: $<?= $product['price'] ?></p>
                    <a href="/product_details.php?id=<?= $product['id'] ?>" class="btn">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Kategori bölümü -->
    <section class="categories">
        <h2>Browse by Categories</h2>
        <div class="category-list">
            <?php foreach ($categories as $category): ?>
                <div class="category-item">
                    <h3><a href="/category.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</div>

<?php
// Ana sayfa için footer yükleniyor
include 'footer.php';
?>
