<?php

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Kullanıcı giriş sayfası
    public function loginPage()
    {
        require_once '../app/views/login.php';
    }

    // Kullanıcı giriş işlemi
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Kullanıcıyı veritabanından kontrol et
            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Başarılı giriş işlemi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: /profile');
            } else {
                // Hatalı giriş
                $error = "Geçersiz kullanıcı adı veya şifre.";
                require_once '../app/views/login.php';
            }
        } else {
            header('Location: /login');
        }
    }

    // Kullanıcı kayıt sayfası
    public function registerPage()
    {
        require_once '../app/views/register.php';
    }

    // Kullanıcı kayıt işlemi
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Şifre doğrulaması
            if ($password !== $confirmPassword) {
                $error = "Şifreler uyuşmuyor.";
                require_once '../app/views/register.php';
                return;
            }

            // Şifreyi güvenli hale getir
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Yeni kullanıcı oluştur
            $newUser = [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
            ];

            // Kullanıcıyı veritabanına kaydet
            $this->userModel->registerUser($newUser);

            // Başarılı kayıt
            $_SESSION['user_name'] = $name;
            header('Location: /login');
        } else {
            header('Location: /register');
        }
    }

    // Kullanıcı çıkış işlemi
    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }

    // Kullanıcı profil sayfası
    public function profile()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = $this->userModel->getUserById($userId);
            require_once '../app/views/profile.php';
        } else {
            header('Location: /login');
        }
    }

    // Şifre sıfırlama sayfası
    public function resetPasswordPage()
    {
        require_once '../app/views/password_reset.php';
    }

    // Şifre sıfırlama işlemi
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            // Şifre sıfırlama mantığı buraya gelir
            $this->userModel->sendPasswordReset($email);
            $message = "Şifre sıfırlama linki gönderildi.";
            require_once '../app/views/password_reset.php';
        }
    }
}

