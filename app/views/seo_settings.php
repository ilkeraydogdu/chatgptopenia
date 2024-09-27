<?php
// seo_settings.php

// SEO Ayarlarının admin tarafından yönetilmesi için kullanılan sayfa
// Bu sayfa, adminin SEO başlıklarını, meta açıklamalarını ve anahtar kelimeleri ayarlamasına olanak tanır.

// Veritabanı bağlantısı dahil et
require_once('../app/config/database.php');
require_once('../app/models/Seo.php');

// Admin giriş kontrolü
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// SEO Modeli
$seoModel = new Seo();

// SEO Ayarlarını Getir
$seoData = $seoModel->getSeoSettings();

// POST işlemi kontrolü (SEO ayarlarını güncelleme)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $meta_description = htmlspecialchars($_POST['meta_description'], ENT_QUOTES, 'UTF-8');
    $meta_keywords = htmlspecialchars($_POST['meta_keywords'], ENT_QUOTES, 'UTF-8');

    // SEO Ayarlarını güncelle
    $seoModel->updateSeoSettings($title, $meta_description, $meta_keywords);

    // Başarılı güncelleme mesajı
    $success_message = "SEO ayarları başarıyla güncellendi.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Ayarları</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>

<div class="container">
    <h2>SEO Ayarları</h2>

    <?php if (isset($success_message)) : ?>
        <div class="alert alert-success">
            <?= $success_message ?>
        </div>
    <?php endif; ?>

    <form action="seo_settings.php" method="POST">
        <div class="form-group">
            <label for="title">SEO Başlığı</label>
            <input type="text" name="title" id="title" value="<?= $seoData['title'] ?>" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Açıklaması</label>
            <textarea name="meta_description" id="meta_description" class="form-control" required><?= $seoData['meta_description'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="meta_keywords">Meta Anahtar Kelimeleri</label>
            <textarea name="meta_keywords" id="meta_keywords" class="form-control" required><?= $seoData['meta_keywords'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ayarları Güncelle</button>
    </form>
</div>

</body>
</html>
