<?php

class HomeController
{
    // Ana sayfayı görüntülemek için kullanılan fonksiyon
    public function index()
    {
        // Ürünleri veya diğer verileri getirme işlemleri
        $productModel = new Product();
        $products = $productModel->getAllActiveProducts();

        // SEO ayarları
        $seo = new Seo();
        $seoData = $seo->getSeoDataForPage('home');

        // Ana sayfa view dosyasını render etme
        require_once 'app/views/home.php';
    }

    // Hakkımızda sayfası için kullanılacak fonksiyon
    public function about()
    {
        // Hakkımızda sayfası için gerekli veriler burada toplanabilir

        // SEO ayarları
        $seo = new Seo();
        $seoData = $seo->getSeoDataForPage('about');

        // Hakkımızda view dosyasını render etme
        require_once 'app/views/about.php';
    }

    // İletişim sayfası için kullanılacak fonksiyon
    public function contact()
    {
        // İletişim formu işleme gibi işlemler burada yapılabilir

        // SEO ayarları
        $seo = new Seo();
        $seoData = $seo->getSeoDataForPage('contact');

        // İletişim view dosyasını render etme
        require_once 'app/views/contact.php';
    }

    // Önerilen ürünler için dinamik bir sistem kullanılabilir
    public function recommendedProducts()
    {
        $productModel = new Product();
        $recommendedProducts = $productModel->getRecommendedProducts();

        // SEO ayarları
        $seo = new Seo();
        $seoData = $seo->getSeoDataForPage('recommended_products');

        // Önerilen ürünler view dosyasını render etme
        require_once 'app/views/recommended_products.php';
    }
}

?>
