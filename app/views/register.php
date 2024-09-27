<?php
// Veritabanı bağlantısı dahil ediliyor
include_once 'app/config/database.php';
include_once 'app/models/User.php';

// Oturum kontrolü
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Kullanıcı modelini çağır
$userModel = new User($db);

// Hata ve başarı mesajları için boş değişkenler oluşturuluyor
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen veriler alınıyor
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Temel doğrulama işlemleri
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Lütfen tüm alanları doldurun.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçersiz e-posta adresi.";
    } elseif ($password !== $confirm_password) {
        $error = "Şifreler eşleşmiyor.";
    } else {
        // Kullanıcının zaten var olup olmadığını kontrol et
        if ($userModel->checkEmailExists($email)) {
            $error = "Bu e-posta adresi ile kayıtlı bir kullanıcı zaten mevcut.";
        } else {
            // Şifreyi güvenli hale getir
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Kullanıcıyı veritabanına kaydet
            if ($userModel->registerUser($username, $email, $hashed_password)) {
                $success = "Kayıt başarılı! Giriş yapabilirsiniz.";
            } else {
                $error = "Kayıt sırasında bir hata oluştu.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>

    <div class="container">
        <h2>Kayıt Ol</h2>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Kullanıcı Adı</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($username) ? $username : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Şifreyi Doğrula</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        </form>

        <p>Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
    </div>

</body>
</html>
