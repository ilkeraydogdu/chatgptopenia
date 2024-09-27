<?php

class PaymentController {
    
    // Veritabanı bağlantısı
    protected $db;

    // Construct ile veritabanı bağlantısını alıyoruz
    public function __construct($database) {
        $this->db = $database;
    }

    // Ödeme işlemi başlatma
    public function initiatePayment($orderId, $paymentMethod, $amount) {
        // Ödeme bilgilerini veritabanına kaydet
        $sql = "INSERT INTO payments (order_id, payment_method, amount, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $status = 'pending'; // Başlangıçta ödeme durumu beklemede
        $stmt->execute([$orderId, $paymentMethod, $amount, $status]);

        // Ödeme işlemi başarıyla başlatıldı mesajı
        return ['success' => true, 'message' => 'Ödeme işlemi başlatıldı.'];
    }

    // Ödeme durumu kontrol etme
    public function checkPaymentStatus($paymentId) {
        // Ödeme durumunu veritabanından al
        $sql = "SELECT status FROM payments WHERE payment_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$paymentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return ['success' => true, 'status' => $result['status']];
        } else {
            return ['success' => false, 'message' => 'Ödeme bilgisi bulunamadı.'];
        }
    }

    // Ödeme tamamlandı
    public function completePayment($paymentId) {
        // Ödeme durumunu güncelle
        $sql = "UPDATE payments SET status = ? WHERE payment_id = ?";
        $stmt = $this->db->prepare($sql);
        $status = 'completed'; // Ödeme tamamlandı durumu
        $stmt->execute([$status, $paymentId]);

        // Sipariş durumu güncelleniyor
        $this->updateOrderStatus($paymentId);

        return ['success' => true, 'message' => 'Ödeme tamamlandı.'];
    }

    // Ödeme iptali
    public function cancelPayment($paymentId) {
        // Ödeme durumunu iptal olarak güncelle
        $sql = "UPDATE payments SET status = ? WHERE payment_id = ?";
        $stmt = $this->db->prepare($sql);
        $status = 'cancelled'; // Ödeme iptal durumu
        $stmt->execute([$status, $paymentId]);

        return ['success' => true, 'message' => 'Ödeme iptal edildi.'];
    }

    // Sipariş durumu güncelleme fonksiyonu
    private function updateOrderStatus($paymentId) {
        // Ödemenin ait olduğu siparişi al
        $sql = "SELECT order_id FROM payments WHERE payment_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$paymentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $orderId = $result['order_id'];
            
            // Sipariş durumu güncelle
            $sql = "UPDATE orders SET status = 'paid' WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
        }
    }
}

?>
