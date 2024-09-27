<?php
// PHPMailer'in yolunu dahil edelim
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../src/Exception.php';
require '../src/PHPMailer.php';
require '../src/SMTP.php';

// PHPMailer örneğini oluştur
$mail = new PHPMailer(true);

try {
    // Sunucu ayarları
    $mail->isSMTP();                                            // SMTP kullanarak gönderim yap
    $mail->Host       = 'smtp.example.com';                     // SMTP sunucusu
    $mail->SMTPAuth   = true;                                   // SMTP kimlik doğrulaması etkin
    $mail->Username   = 'user@example.com';                     // SMTP kullanıcı adı
    $mail->Password   = 'secret';                               // SMTP şifresi
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // TLS şifreleme
    $mail->Port       = 587;                                    // SMTP portu

    // Alıcılar
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     // Alıcı
    $mail->addAddress('ellen@example.com');               // Ek bir alıcı
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    // Ekler
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Ek dosya
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Ek dosyayı yeniden adlandırarak

    // İçerik
    $mail->isHTML(true);                                  // HTML içerik
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Mesaj başarıyla gönderildi';
} catch (Exception $e) {
    echo "Mesaj gönderilemedi. Hata: {$mail->ErrorInfo}";
}
