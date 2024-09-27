<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerController
{
    private $mail;

    public function __construct()
    {
        // PHPMailer başlatılır.
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP(); // SMTP kullanılıyor.
        $this->mail->Host = 'smtp.example.com'; // SMTP sunucusu
        $this->mail->SMTPAuth = true; // SMTP kimlik doğrulama
        $this->mail->Username = 'your_email@example.com'; // SMTP kullanıcı adı
        $this->mail->Password = 'your_password'; // SMTP şifresi
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Güvenli bağlantı (STARTTLS)
        $this->mail->Port = 587; // SMTP portu
        $this->mail->setFrom('your_email@example.com', 'Your Name'); // Gönderen bilgileri
    }

    // E-posta gönderme fonksiyonu
    public function sendEmail($to, $subject, $body)
    {
        try {
            // Alıcı bilgisi
            $this->mail->addAddress($to);

            // E-posta başlığı ve içeriği
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            // E-posta gönderme işlemi
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Hata durumunda mesaj döndürülür.
            return 'E-posta gönderimi başarısız oldu. Hata: ' . $this->mail->ErrorInfo;
        }
    }

    // Şifre sıfırlama için e-posta gönderme fonksiyonu
    public function sendPasswordReset($to, $resetLink)
    {
        $subject = "Şifre Sıfırlama Talebi";
        $body = "Şifre sıfırlama işlemi için aşağıdaki linke tıklayın:<br><a href='$resetLink'>$resetLink</a>";
        return $this->sendEmail($to, $subject, $body);
    }

    // Sipariş onayı için e-posta gönderme fonksiyonu
    public function sendOrderConfirmation($to, $orderDetails)
    {
        $subject = "Sipariş Onayınız";
        $body = "Siparişiniz başarıyla alınmıştır.<br><br>Detaylar:<br>" . $orderDetails;
        return $this->sendEmail($to, $subject, $body);
    }
}
