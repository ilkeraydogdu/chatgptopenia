<?php
// Header content type as XML
header("Content-Type: application/xml; charset=utf-8");

// Start XML file
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Include your database connection file
require_once '../app/config/database.php';

// Create database connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection error
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Query for dynamic URLs, for example, pages and products
// Fetching dynamic pages
$page_query = "SELECT slug, updated_at FROM pages WHERE status = 'active'";
$page_result = $db->query($page_query);

if ($page_result->num_rows > 0) {
    while ($row = $page_result->fetch_assoc()) {
        echo '<url>';
        echo '<loc>' . 'https://www.yourwebsite.com/' . $row['slug'] . '</loc>';
        echo '<lastmod>' . date('Y-m-d', strtotime($row['updated_at'])) . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.8</priority>';
        echo '</url>';
    }
}

// Fetching products
$product_query = "SELECT slug, updated_at FROM products WHERE status = 'active'";
$product_result = $db->query($product_query);

if ($product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        echo '<url>';
        echo '<loc>' . 'https://www.yourwebsite.com/product/' . $row['slug'] . '</loc>';
        echo '<lastmod>' . date('Y-m-d', strtotime($row['updated_at'])) . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.8</priority>';
        echo '</url>';
    }
}

// Add static URLs if necessary
$static_urls = [
    '/' => '2024-01-01',
    '/about-us' => '2024-01-05',
    '/contact' => '2024-01-03',
];

foreach ($static_urls as $url => $lastmod) {
    echo '<url>';
    echo '<loc>https://www.yourwebsite.com' . $url . '</loc>';
    echo '<lastmod>' . $lastmod . '</lastmod>';
    echo '<changefreq>monthly</changefreq>';
    echo '<priority>0.6</priority>';
    echo '</url>';
}

// Close database connection
$db->close();

// Close XML file
echo '</urlset>';
?>
