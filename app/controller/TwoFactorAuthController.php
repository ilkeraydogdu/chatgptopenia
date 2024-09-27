<?php

class TwoFactorAuthController
{
    private $userModel;
    private $twoFactorAuthModel;
    private $mailer;

    public function __construct()
    {
        $this->userModel = new User();
        $this->twoFactorAuthModel = new TwoFactorAuth();
        $this->mailer = new Mailer();
    }

    // İki adımlı doğrulama sayfasını yükler
    public function showTwoFactorAuthForm($userId)
    {
        // Kullanıcının 2FA durumunu kontrol et
        $user = $this->userModel->findUserById($userId);
        if ($user['two_factor_enabled']) {
            // Kullanıcı iki adımlı doğrulama kodu almışsa sayfayı göster
            require_once '../app/views/two_factor_auth.php';
        } else {
            header('Location: /login');
        }
    }

    // E-posta ile doğrulama kodu gönderir
    public function sendEmailVerification($userId)
    {
        $user = $this->userModel->findUserById($userId);

        // Rastgele doğrulama kodu üret
        $verificationCode = $this->generateVerificationCode();

        // Doğrulama kodunu veritabanına kaydet
        $this->twoFactorAuthModel->saveVerificationCode($userId, $verificationCode, 'email');

        // E-postayı gönder
        $subject = "Two-Factor Authentication Code";
        $message = "Your two-factor authentication code is: $verificationCode";

        $this->mailer->send($user['email'], $subject, $message);
    }

    // SMS ile doğrulama kodu gönderir (SMS API ile entegre)
    public function sendSMSVerification($userId)
    {
        $user = $this->userModel->findUserById($userId);

        // Rastgele doğrulama kodu üret
        $verificationCode = $this->generateVerificationCode();

        // Doğrulama kodunu veritabanına kaydet
        $this->twoFactorAuthModel->saveVerificationCode($userId, $verificationCode, 'sms');

        // SMS API kullanarak SMS gönderimi (örneğin, Twilio API)
        $smsService = new SMSService();
        $smsService->send($user['phone'], "Your 2FA code is: $verificationCode");
    }

    // Kullanıcının girdiği doğrulama kodunu kontrol eder
    public function verifyCode($userId, $inputCode, $method)
    {
        $storedCode = $this->twoFactorAuthModel->getVerificationCode($userId, $method);

        if ($storedCode === $inputCode) {
            // Doğrulama başarılı
            $this->twoFactorAuthModel->clearVerificationCode($userId, $method);
            $_SESSION['two_factor_authenticated'] = true;
            header('Location: /dashboard');
        } else {
            // Yanlış kod
            $_SESSION['error'] = "Invalid authentication code.";
            header('Location: /twofactorauth');
        }
    }

    // Rastgele doğrulama kodu üretir
    private function generateVerificationCode()
    {
        return rand(100000, 999999); // 6 haneli bir rastgele kod üretir
    }
}

