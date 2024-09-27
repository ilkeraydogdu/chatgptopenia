<?php

class Report
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    // Kullanıcı bazlı raporlama
    public function getUserReports($userId)
    {
        $query = "SELECT * FROM orders WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ürün bazlı raporlama
    public function getProductReports($productId)
    {
        $query = "SELECT * FROM orders WHERE product_id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En çok satan ürünler
    public function getTopSellingProducts()
    {
        $query = "SELECT product_id, COUNT(*) AS sales_count 
        FROM orders 
        GROUP BY product_id 
        ORDER BY sales_count DESC 
        LIMIT 10";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En çok görüntülenen ama alınmayan ürünler
    public function getMostViewedButNotBoughtProducts()
    {
        $query = "SELECT p.id, p.name, COUNT(v.id) AS view_count 
        FROM products p 
        LEFT JOIN product_views v ON p.id = v.product_id 
        WHERE p.id NOT IN (SELECT product_id FROM orders) 
        GROUP BY p.id 
        ORDER BY view_count DESC 
        LIMIT 10";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Satış trendlerini analiz eden rapor
    public function getSalesTrends($startDate, $endDate)
    {
        $query = "SELECT DATE(order_date) as date, COUNT(*) as sales_count 
        FROM orders 
        WHERE order_date BETWEEN :start_date AND :end_date 
        GROUP BY DATE(order_date)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kullanıcı segmentasyonuna dayalı raporlama
    public function getUserSegmentationReports()
    {
        $query = "SELECT u.id, u.name, COUNT(o.id) as total_orders, SUM(o.total_price) as total_spent 
        FROM users u 
        LEFT JOIN orders o ON u.id = o.user_id 
        GROUP BY u.id 
        ORDER BY total_spent DESC";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
