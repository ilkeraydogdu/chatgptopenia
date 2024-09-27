<?php
// PageManagement.php
// Sayfa yönetimi için gerekli kütüphaneler ve bağlantılar
require_once '../app/models/Page.php';
require_once '../app/controllers/PageController.php';

// Oturum kontrolü
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Sayfa yönetimi işlemleri
$pageController = new PageController();
$pages = $pageController->getAllPages();

// Yeni sayfa ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_page'])) {
    $pageTitle = $_POST['page_title'];
    $pageContent = $_POST['page_content'];
    $seoMeta = $_POST['seo_meta'];
    $seoKeywords = $_POST['seo_keywords'];

    // Yeni sayfa oluştur
    $pageController->createPage($pageTitle, $pageContent, $seoMeta, $seoKeywords);
    header('Location: page_management.php');
    exit();
}

// Sayfa silme işlemi
if (isset($_GET['delete_id'])) {
    $pageId = $_GET['delete_id'];
    $pageController->deletePage($pageId);
    header('Location: page_management.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Management</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Sayfa Yönetimi</h1>

        <h2>Mevcut Sayfalar</h2>
        <table>
            <thead>
                <tr>
                    <th>Sayfa Başlığı</th>
                    <th>SEO Meta</th>
                    <th>SEO Anahtar Kelimeler</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= htmlspecialchars($page['title']) ?></td>
                        <td><?= htmlspecialchars($page['seo_meta']) ?></td>
                        <td><?= htmlspecialchars($page['seo_keywords']) ?></td>
                        <td>
                            <a href="edit_page.php?id=<?= $page['id'] ?>">Düzenle</a>
                            <a href="page_management.php?delete_id=<?= $page['id'] ?>" onclick="return confirm('Bu sayfayı silmek istediğinize emin misiniz?')">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Yeni Sayfa Ekle</h2>
        <form action="page_management.php" method="POST">
            <div>
                <label for="page_title">Sayfa Başlığı:</label>
                <input type="text" name="page_title" id="page_title" required>
            </div>
            <div>
                <label for="page_content">Sayfa İçeriği:</label>
                <textarea name="page_content" id="page_content" required></textarea>
            </div>
            <div>
                <label for="seo_meta">SEO Meta:</label>
                <input type="text" name="seo_meta" id="seo_meta" required>
            </div>
            <div>
                <label for="seo_keywords">SEO Anahtar Kelimeler:</label>
                <input type="text" name="seo_keywords" id="seo_keywords" required>
            </div>
            <button type="submit" name="add_page">Sayfa Ekle</button>
        </form>
    </div>
</body>
</html>
