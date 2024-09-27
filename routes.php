<?php
// Routes configuration for the application

// Home Route
$router->get('/', 'HomeController@index');

// User Authentication Routes
$router->get('/login', 'UserController@showLogin');
$router->post('/login', 'UserController@login');
$router->get('/register', 'UserController@showRegister');
$router->post('/register', 'UserController@register');
$router->get('/logout', 'UserController@logout');

// Product Routes
$router->get('/products', 'ProductController@index');
$router->get('/product/{id}', 'ProductController@show');
$router->post('/product/add', 'ProductController@add');
$router->post('/product/edit/{id}', 'ProductController@edit');
$router->post('/product/delete/{id}', 'ProductController@delete');
$router->post('/product/toggleStatus/{id}', 'ProductController@toggleStatus');

// Order Routes
$router->get('/orders', 'OrderController@index');
$router->get('/order/{id}', 'OrderController@show');
$router->post('/order/add', 'OrderController@add');
$router->post('/order/cancel/{id}', 'OrderController@cancel');

// Report Routes
$router->get('/reports/users', 'ReportController@userReports');
$router->get('/reports/products', 'ReportController@productReports');

// Store Management Routes
$router->get('/stores', 'StoreController@index');
$router->post('/store/add', 'StoreController@add');
$router->post('/store/edit/{id}', 'StoreController@edit');
$router->post('/store/delete/{id}', 'StoreController@delete');

// Dynamic Page Routes
$router->get('/page/{slug}', 'PageController@show');

// SEO Management Routes
$router->get('/seo', 'SeoController@index');
$router->post('/seo/update', 'SeoController@update');

// Two-Factor Authentication Routes
$router->get('/2fa/setup', 'TwoFactorAuthController@setup');
$router->post('/2fa/verify', 'TwoFactorAuthController@verify');

// Sitemap Generation Route
$router->get('/sitemap.xml', 'SitemapController@generate');

// Fallback Route (404 Not Found)
$router->setFallback('ErrorController@notFound');

?>
