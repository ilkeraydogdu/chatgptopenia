<?php

class SitemapController {

    // Veritabanı bağlantısını tutan değişken
    private $db;

    public function __construct() {
        // Veritabanı bağlantısını al
        $this->db = Database::getConnection();
    }

    // Sitemap oluşturma fonksiyonu
    public function generateSitemap() {
        // Başlangıç XML yapısı
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?> <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        // Dinamik olarak sayfalar, ürünler ve diğer içerikler için URL'leri ekle
        $this->addStaticUrls($xml);
        $this->addProductUrls($xml);
        $this->addCategoryUrls($xml);
        $this->addPageUrls($xml);

        // XML çıktısını oluştur
        header("Content-Type: application/xml; charset=utf-8");
        echo $xml->asXML();
    }

    // Statik URL'leri ekleyen fonksiyon
    private function addStaticUrls($xml) {
        $staticUrls = [
            ['loc' => base_url(), 'priority' => '1.0'],
            ['loc' => base_url('about'), 'priority' => '0.8'],
            ['loc' => base_url('contact'), 'priority' => '0.8'],
        ];

        foreach ($staticUrls as $url) {
            $this->addUrl($xml, $url['loc'], $url['priority']);
        }
    }

    // Ürün URL'lerini ekleyen fonksiyon
    private function addProductUrls($xml) {
        $query = "SELECT slug, updated_at FROM products WHERE status = 'active'";
        $stmt = $this->db->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $url = base_url('product/' . $row['slug']);
            $this->addUrl($xml, $url, '0.9', $row['updated_at']);
        }
    }

    // Kategori URL'lerini ekleyen fonksiyon
    private function addCategoryUrls($xml) {
        $query = "SELECT slug, updated_at FROM categories WHERE status = 'active'";
        $stmt = $this->db->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $url = base_url('category/' . $row['slug']);
            $this->addUrl($xml, $url, '0.8', $row['updated_at']);
        }
    }

    // Dinamik sayfa URL'lerini ekleyen fonksiyon
    private function addPageUrls($xml) {
        $query = "SELECT slug, updated_at FROM pages WHERE status = 'published'";
        $stmt = $this->db->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $url = base_url('page/' . $row['slug']);
            $this->addUrl($xml, $url, '0.7', $row['updated_at']);
        }
    }

    // URL'leri XML'e ekleyen yardımcı fonksiyon
    private function addUrl($xml, $loc, $priority, $lastmod = null) {
        $url = $xml->addChild('url');
        $url->addChild('loc', htmlspecialchars($loc));
        $url->addChild('priority', $priority);
        
        if ($lastmod) {
            $url->addChild('lastmod', date(DATE_W3C, strtotime($lastmod)));
        }
    }
}
