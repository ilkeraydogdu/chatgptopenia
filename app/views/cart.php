<?php
// Sepet sayfası
session_start();

// Eğer kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Veritabanı bağlantısını içe aktar
include_once 'app/config/database.php';
include_once 'app/models/Product.php';

// Sepet kontrolü
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Sepet toplamı
$total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="cart-container">
        <h1>Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $product_id => $quantity): ?>
                        <?php
                        // Ürün bilgilerini veritabanından çek
                        $product = Product::find($product_id);
                        if (!$product) continue;
                        $subtotal = $product['price'] * $quantity;
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($quantity); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?> USD</td>
                            <td><?php echo htmlspecialchars($subtotal); ?> USD</td>
                            <td>
                                <a href="remove_from_cart.php?product_id=<?php echo $product_id; ?>">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Total: <?php echo $total; ?> USD</h3>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
