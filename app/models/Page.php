<?php

class Page
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Sayfa ekleme fonksiyonu
    public function addPage($title, $content, $metaTitle = '', $metaDescription = '')
    {
        $sql = "INSERT INTO pages (title, content, meta_title, meta_description, created_at) 
        VALUES (:title, :content, :meta_title, :meta_description, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':meta_title', $metaTitle);
        $stmt->bindParam(':meta_description', $metaDescription);
        return $stmt->execute();
    }

    // Sayfa güncelleme fonksiyonu
    public function updatePage($id, $title, $content, $metaTitle = '', $metaDescription = '')
    {
        $sql = "UPDATE pages SET title = :title, content = :content, meta_title = :meta_title, meta_description = :meta_description 
        WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':meta_title', $metaTitle);
        $stmt->bindParam(':meta_description', $metaDescription);
        return $stmt->execute();
    }

    // Tüm sayfaları listeleme fonksiyonu
    public function getPages()
    {
        $sql = "SELECT * FROM pages ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tek bir sayfayı ID ile getirme fonksiyonu
    public function getPageById($id)
    {
        $sql = "SELECT * FROM pages WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Sayfa silme fonksiyonu
    public function deletePage($id)
    {
        $sql = "DELETE FROM pages WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
