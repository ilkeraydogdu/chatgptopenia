<?php

// Oturum başlat
session_start();

// Otomatik yükleme (Autoloader)
require_once 'app/config/database.php';
require_once 'routes.php'; // Route yapılandırmaları

// Hata ayıklama için (development ortamında)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Gelen URL'yi çözümle
$request_uri = $_SERVER['REQUEST_URI'];

// Route yapısına göre yönlendirme
$router = new Router();
$router->direct($request_uri);

// Yönlendirme sonucu sayfa görüntüleme işlemi
if ($router->isRouteMatched()) {
    require $router->getMatchedController();
} else {
    // Sayfa bulunamadıysa (404 hata sayfası)
    http_response_code(404);
    echo '404 - Sayfa Bulunamadı';
}
