<?php

class Order {
    
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Sipariş oluşturma fonksiyonu
    public function createOrder($userId, $products, $totalPrice, $orderNotes = null) {
        $orderNumber = $this->generateOrderNumber();
        $date = date('Y-m-d H:i:s');

        // Siparişi veritabanına ekleyelim
        $query = "INSERT INTO orders (user_id, order_number, total_price, order_date, order_notes)
        VALUES (:userId, :orderNumber, :totalPrice, :orderDate, :orderNotes)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':orderNumber', $orderNumber);
        $stmt->bindParam(':totalPrice', $totalPrice);
        $stmt->bindParam(':orderDate', $date);
        $stmt->bindParam(':orderNotes', $orderNotes);
        
        if ($stmt->execute()) {
            $orderId = $this->db->lastInsertId();

            // Sipariş ürünlerini ekleyelim
            foreach ($products as $product) {
                $this->addOrderItem($orderId, $product['product_id'], $product['quantity']);
            }
            return $orderId;
        }

        return false;
    }

    // Sipariş numarası üretme fonksiyonu
    private function generateOrderNumber() {
        // Mağaza ya da admin'e göre sipariş numarası formatı üretme
        // Burada bir mağaza ya da kullanıcı ID'sine dayalı özelleştirilmiş numara üretilebilir
        return 'ORD-' . strtoupper(uniqid());
    }

    // Sipariş ürünlerini veritabanına ekleyen fonksiyon
    private function addOrderItem($orderId, $productId, $quantity) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity)
        VALUES (:orderId, :productId, :quantity)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
    }

    // Sipariş bilgilerini alma fonksiyonu
    public function getOrder($orderId) {
        $query = "SELECT * FROM orders WHERE id = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kullanıcıya ait tüm siparişleri alma fonksiyonu
    public function getOrdersByUser($userId) {
        $query = "SELECT * FROM orders WHERE user_id = :userId ORDER BY order_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sipariş durumunu güncelleme fonksiyonu
    public function updateOrderStatus($orderId, $status) {
        $query = "UPDATE orders SET status = :status WHERE id = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':orderId', $orderId);
        return $stmt->execute();
    }

    // Siparişin ürünlerini alma fonksiyonu
    public function getOrderItems($orderId) {
        $query = "SELECT * FROM order_items WHERE order_id = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Siparişi silme fonksiyonu
    public function deleteOrder($orderId) {
        // Öncelikle sipariş ürünlerini silelim
        $query = "DELETE FROM order_items WHERE order_id = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();

        // Şimdi siparişi silelim
        $query = "DELETE FROM orders WHERE id = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        return $stmt->execute();
    }
}
?>
