<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $seo_description; ?>">
    <meta name="keywords" content="<?php echo $seo_keywords; ?>">
    <title><?php echo $page_title; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="/public/assets/images/favicon.ico" type="image/x-icon">

    <!-- CSS Files -->
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="/public/assets/css/responsive.css">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- JS Files -->
    <script src="/public/assets/js/app.js" defer></script>
</head>
<body>

    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <a href="/"><img src="/public/assets/images/logo.png" alt="Site Logo"></a>
                </div>
                <div class="menu">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/products">Products</a></li>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="/profile">My Profile</a></li>
                            <li><a href="/logout">Logout</a></li>
                        <?php else: ?>
                            <li><a href="/login">Login</a></li>
                            <li><a href="/register">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Wrapper -->
    <div class="main-content">
