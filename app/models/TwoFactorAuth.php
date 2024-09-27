<?php

class TwoFactorAuth
{
    private $db;
    private $user;

    public function __construct($db, $user)
    {
        $this->db = $db;
        $this->user = $user;
    }

    // Rastgele bir doğrulama kodu üretir
    public function generateCode()
    {
        return rand(100000, 999999); // 6 haneli bir kod üret
    }

    // Kullanıcıya doğrulama kodunu e-posta ile gönderir
    public function sendEmailVerification($email, $code)
    {
        $subject = "İki Adımlı Doğrulama Kodu";
        $message = "Merhaba, doğrulama kodunuz: $code";
        $headers = "From: noreply@yourwebsite.com";

        return mail($email, $subject, $message, $headers);
    }

    // Kullanıcıya doğrulama kodunu SMS ile gönderir
    public function sendSMSVerification($phone, $code)
    {
        // SMS API kullanarak doğrulama kodunu gönder
        // Örneğin: Twilio API, Nexmo API vb. kullanılabilir
        $smsApi = new SMSAPI(); // SMS API'yi entegre ettiğin yer
        return $smsApi->send($phone, "Doğrulama kodunuz: $code");
    }

    // Kullanıcının doğrulama kodunu veritabanında saklar
    public function storeVerificationCode($userId, $code)
    {
        $stmt = $this->db->prepare("INSERT INTO two_factor_auth (user_id, code, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$userId, $code]);
    }

    // Verilen doğrulama kodunu kontrol eder
    public function verifyCode($userId, $code)
    {
        $stmt = $this->db->prepare("SELECT * FROM two_factor_auth WHERE user_id = ? AND code = ? AND created_at >= NOW() - INTERVAL 10 MINUTE");
        $stmt->execute([$userId, $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Doğrulama kodunu veritabanından siler
    public function removeVerificationCode($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM two_factor_auth WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}
