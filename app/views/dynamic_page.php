<?php
// dynamic_page.php

// Gerekli dosyaları dahil et
require_once '../app/config/database.php';
require_once '../app/models/Page.php';

// URL'den gelen sayfa bilgisi alınır
$pageSlug = isset($_GET['slug']) ? $_GET['slug'] : null;

if ($pageSlug) {
    // Veritabanında ilgili sayfa slug'ına göre sayfa bilgisi çekilir
    $pageModel = new Page();
    $pageData = $pageModel->getPageBySlug($pageSlug);

    if ($pageData) {
        // Sayfa başlığı, içeriği ve diğer bilgileri ekrana bas
        $pageTitle = $pageData['title'];
        $pageContent = $pageData['content'];

        // Sayfa başlığını ve meta etiketlerini SEO uyumlu hale getir
        echo "<title>" . htmlspecialchars($pageTitle) . "</title>";
        echo "<meta name='description' content='" . htmlspecialchars($pageData['meta_description']) . "'>";
        echo "<meta name='keywords' content='" . htmlspecialchars($pageData['meta_keywords']) . "'>";

        // Header'ı dahil et
        require_once '../app/views/header.php';

        // Sayfa içeriğini göster
        echo "<div class='page-content'>";
        echo "<h1>" . htmlspecialchars($pageTitle) . "</h1>";
        echo "<div>" . $pageContent . "</div>";
        echo "</div>";

        // Footer'ı dahil et
        require_once '../app/views/footer.php';
    } else {
        // Eğer sayfa bulunamazsa 404 sayfasına yönlendir
        header("HTTP/1.0 404 Not Found");
        require_once '../app/views/404.php';
    }
} else {
    // Sayfa slug'ı yoksa anasayfaya yönlendir
    header("Location: /");
}
?>
