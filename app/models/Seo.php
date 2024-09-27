<?php

class Seo
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Sayfa ve ürünler için SEO ayarlarını al.
     * 
     * @param string $type - 'page' ya da 'product'
     * @param int $id - Sayfa ya da ürün ID'si
     * @return array - SEO bilgileri
     */
    public function getSeoData($type, $id)
    {
        $sql = "SELECT title, description, keywords FROM seo WHERE type = :type AND item_id = :id";
        $query = $this->db->prepare($sql);
        $query->bindParam(':type', $type);
        $query->bindParam(':id', $id);
        $query->execute();
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * SEO ayarlarını güncelle.
     * 
     * @param string $type - 'page' ya da 'product'
     * @param int $id - Sayfa ya da ürün ID'si
     * @param string $title - SEO başlığı
     * @param string $description - SEO açıklaması
     * @param string $keywords - SEO anahtar kelimeleri
     * @return bool - Güncelleme durumu
     */
    public function updateSeoData($type, $id, $title, $description, $keywords)
    {
        $sql = "UPDATE seo SET title = :title, description = :description, keywords = :keywords WHERE type = :type AND item_id = :id";
        $query = $this->db->prepare($sql);
        $query->bindParam(':type', $type);
        $query->bindParam(':id', $id);
        $query->bindParam(':title', $title);
        $query->bindParam(':description', $description);
        $query->bindParam(':keywords', $keywords);

        return $query->execute();
    }

    /**
     * Yeni bir SEO kaydı ekle.
     * 
     * @param string $type - 'page' ya da 'product'
     * @param int $id - Sayfa ya da ürün ID'si
     * @param string $title - SEO başlığı
     * @param string $description - SEO açıklaması
     * @param string $keywords - SEO anahtar kelimeleri
     * @return bool - Ekleme durumu
     */
    public function addSeoData($type, $id, $title, $description, $keywords)
    {
        $sql = "INSERT INTO seo (type, item_id, title, description, keywords) VALUES (:type, :id, :title, :description, :keywords)";
        $query = $this->db->prepare($sql);
        $query->bindParam(':type', $type);
        $query->bindParam(':id', $id);
        $query->bindParam(':title', $title);
        $query->bindParam(':description', $description);
        $query->bindParam(':keywords', $keywords);

        return $query->execute();
    }

    /**
     * SEO kaydını sil.
     * 
     * @param string $type - 'page' ya da 'product'
     * @param int $id - Sayfa ya da ürün ID'si
     * @return bool - Silme durumu
     */
    public function deleteSeoData($type, $id)
    {
        $sql = "DELETE FROM seo WHERE type = :type AND item_id = :id";
        $query = $this->db->prepare($sql);
        $query->bindParam(':type', $type);
        $query->bindParam(':id', $id);

        return $query->execute();
    }
}
