<?php
// Header ayarları - XML çıktısı olması için gerekli
header('Content-Type: application/xml; charset=utf-8');

// Veritabanı bağlantısı
require_once '../app/config/database.php'; // Veritabanı dosyasının yolu

// XML çıktısı için başlangıç
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Dinamik olarak sitenizin URL'lerini ekleyeceğiz.
// Ana sayfa
echo '<url>';
echo '<loc>' . 'http://www.siteniz.com/' . '</loc>';
echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
echo '<changefreq>daily</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

// Veritabanından dinamik sayfalar ve ürünler çekiliyor
// Ürünler için örnek
$query = "SELECT slug, updated_at FROM products WHERE status = 'active'";
$result = $db->query($query);

while ($row = $result->fetch_assoc()) {
    echo '<url>';
    echo '<loc>' . 'http://www.siteniz.com/urun/' . $row['slug'] . '</loc>';
    echo '<lastmod>' . date('Y-m-d', strtotime($row['updated_at'])) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

// Dinamik sayfalar için örnek
$query_pages = "SELECT slug, updated_at FROM pages WHERE status = 'published'";
$result_pages = $db->query($query_pages);

while ($row_pages = $result_pages->fetch_assoc()) {
    echo '<url>';
    echo '<loc>' . 'http://www.siteniz.com/sayfa/' . $row_pages['slug'] . '</loc>';
    echo '<lastmod>' . date('Y-m-d', strtotime($row_pages['updated_at'])) . '</lastmod>';
    echo '<changefreq>monthly</changefreq>';
    echo '<priority>0.6</priority>';
    echo '</url>';
}

// Sitemap kapanış etiketi
echo '</urlset>';
?>
