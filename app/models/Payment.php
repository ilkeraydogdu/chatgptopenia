<?php

class Payment
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Kullanıcının ödeme işlemi kaydı
    public function createPayment($order_id, $user_id, $amount, $payment_method)
    {
        $sql = "INSERT INTO payments (order_id, user_id, amount, payment_method, status, created_at) 
        VALUES (:order_id, :user_id, :amount, :payment_method, :status, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':order_id', $order_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':payment_method', $payment_method);
        $stmt->bindValue(':status', 'pending');
        return $stmt->execute();
    }

    // Ödeme işlemi detayları
    public function getPaymentDetails($payment_id)
    {
        $sql = "SELECT * FROM payments WHERE id = :payment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':payment_id', $payment_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kullanıcının ödeme geçmişi
    public function getUserPayments($user_id)
    {
        $sql = "SELECT * FROM payments WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ödeme durumu güncelleme
    public function updatePaymentStatus($payment_id, $status)
    {
        $sql = "UPDATE payments SET status = :status WHERE id = :payment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':payment_id', $payment_id);
        $stmt->bindValue(':status', $status);
        return $stmt->execute();
    }

    // Kullanıcıya özel sipariş ödeme kontrolü
    public function checkUserOrderPayment($order_id, $user_id)
    {
        $sql = "SELECT * FROM payments WHERE order_id = :order_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':order_id', $order_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
