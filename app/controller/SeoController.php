<?php

class SeoController
{
    private $seoModel;

    public function __construct()
    {
        $this->seoModel = new Seo();
    }

    // Sayfa için SEO bilgilerini ayarlama
    public function setSeoForPage($pageId, $title, $description, $keywords)
    {
        // SEO bilgilerini veritabanına kaydet
        return $this->seoModel->setSeo($pageId, $title, $description, $keywords);
    }

    // Ürün için SEO bilgilerini ayarlama
    public function setSeoForProduct($productId, $title, $description, $keywords)
    {
        // SEO bilgilerini veritabanına kaydet
        return $this->seoModel->setSeo($productId, $title, $description, $keywords, 'product');
    }

    // Dinamik SEO bilgilerini çekme
    public function getSeoData($identifier, $type = 'page')
    {
        // SEO bilgilerini veritabanından çek
        return $this->seoModel->getSeo($identifier, $type);
    }

    // Dinamik SEO ayarları sayfası için verileri çekme
    public function seoSettingsPage()
    {
        // Tüm sayfa ve ürünlerin SEO bilgilerini listelemek için veri çekme
        $allSeoData = $this->seoModel->getAllSeoData();
        require_once 'views/seo_settings.php'; // SEO ayarları sayfası görüntülenir
    }
}
