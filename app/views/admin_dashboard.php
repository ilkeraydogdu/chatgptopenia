<?php
// admin_dashboard.php

// Admin oturum kontrolü
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Veritabanı bağlantısı
require_once '../app/config/database.php';
require_once '../app/models/Order.php';
require_once '../app/models/User.php';
require_once '../app/models/Product.php';

// Toplam sipariş sayısını çek
$orderModel = new Order();
$totalOrders = $orderModel->getTotalOrders();

// Toplam kullanıcı sayısını çek
$userModel = new User();
$totalUsers = $userModel->getTotalUsers();

// Toplam ürün sayısını çek
$productModel = new Product();
$totalProducts = $productModel->getTotalProducts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Panel</h1>
        
        <div class="stats">
            <div class="stat-item">
                <h3>Toplam Siparişler</h3>
                <p><?php echo $totalOrders; ?></p>
            </div>
            <div class="stat-item">
                <h3>Toplam Kullanıcılar</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="stat-item">
                <h3>Toplam Ürünler</h3>
                <p><?php echo $totalProducts; ?></p>
            </div>
        </div>

        <div class="admin-actions">
            <h2>Yönetim İşlemleri</h2>
            <ul>
                <li><a href="manage_users.php">Kullanıcıları Yönet</a></li>
                <li><a href="manage_products.php">Ürünleri Yönet</a></li>
                <li><a href="manage_orders.php">Siparişleri Yönet</a></li>
                <li><a href="seo_settings.php">SEO Ayarları</a></li>
                <li><a href="payment_methods.php">Ödeme Yöntemleri</a></li>
                <li><a href="logout.php">Çıkış Yap</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
