<?php

class TwoFactorAuth {
    private $db;  // Veritabanı bağlantısı
    private $user;

    public function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }

    // 2FA için rastgele bir doğrulama kodu oluşturur
    public function generateVerificationCode() {
        return rand(100000, 999999);  // 6 haneli bir doğrulama kodu oluşturulur
    }

    // Doğrulama kodunu belirli bir kullanıcıya e-posta veya SMS yoluyla gönderir
    public function sendVerificationCode($method = 'email') {
        $code = $this->generateVerificationCode();
        $expiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));  // Kodun geçerlilik süresi 10 dakika

        // Kodun veritabanına kaydedilmesi
        $this->saveVerificationCode($this->user['id'], $code, $expiration);

        if ($method === 'email') {
            $this->sendEmail($this->user['email'], $code);  // E-posta ile gönder
        } elseif ($method === 'sms') {
            $this->sendSMS($this->user['phone'], $code);  // SMS ile gönder
        }
    }

    // Doğrulama kodunu veritabanına kaydeder
    private function saveVerificationCode($userId, $code, $expiration) {
        $stmt = $this->db->prepare("INSERT INTO two_factor_codes (user_id, code, expiration) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $code, $expiration]);
    }

    // E-posta ile doğrulama kodu gönderme işlemi
    private function sendEmail($email, $code) {
        // PHPMailer veya başka bir mailer ile e-posta gönderimi
        $subject = "Your Two-Factor Authentication Code";
        $message = "Your verification code is: $code";

        // Mail gönderim işlemi (PHPMailer kullanılarak)
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';  // SMTP sunucusu
            $mail->SMTPAuth = true;
            $mail->Username = 'user@example.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@example.com', 'Your Company');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    // SMS ile doğrulama kodu gönderme işlemi
    private function sendSMS($phone, $code) {
        // SMS API kullanarak mesaj gönderme işlemi
        $message = "Your verification code is: $code";

        // SMS API entegrasyonu burada olacak
        // Örneğin, Twilio gibi bir hizmet kullanılabilir.
    }

    // Kullanıcının doğrulama kodunu doğrulamak için
    public function verifyCode($userId, $inputCode) {
        $stmt = $this->db->prepare("SELECT code, expiration FROM two_factor_codes WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();

        if ($result && $result['code'] == $inputCode && strtotime($result['expiration']) > time()) {
            return true;  // Kod doğru ve geçerlilik süresi dolmamış
        }

        return false;  // Kod yanlış veya süresi dolmuş
    }
}

?>
