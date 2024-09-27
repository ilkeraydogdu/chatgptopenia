<?php
// mailer_settings.php

// Kullanıcı giriş kontrolü
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Veritabanı bağlantısı
require_once '../app/config/database.php';

// Başlık ve yan menü dahil ediliyor
include 'header.php';

// Mail ayarları veritabanından çekiliyor
$query = "SELECT * FROM mail_settings";
$result = $conn->query($query);
$mail_settings = $result->fetch_assoc();

// Form gönderildiğinde mail ayarlarını güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = $_POST['smtp_host'];
    $port = $_POST['smtp_port'];
    $username = $_POST['smtp_username'];
    $password = $_POST['smtp_password'];
    $from_email = $_POST['from_email'];
    $from_name = $_POST['from_name'];

    $update_query = "UPDATE mail_settings SET smtp_host='$host', smtp_port='$port', smtp_username='$username', smtp_password='$password', from_email='$from_email', from_name='$from_name' WHERE id=1";

    if ($conn->query($update_query) === TRUE) {
        echo "<div class='alert alert-success'>Mail ayarları güncellendi!</div>";
    } else {
        echo "<div class='alert alert-danger'>Hata: Mail ayarları güncellenemedi.</div>";
    }
}
?>

<div class="container">
    <h2>Mail Ayarları</h2>
    <form method="POST" action="mailer_settings.php">
        <div class="form-group">
            <label for="smtp_host">SMTP Host:</label>
            <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php echo $mail_settings['smtp_host']; ?>" required>
        </div>
        <div class="form-group">
            <label for="smtp_port">SMTP Port:</label>
            <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo $mail_settings['smtp_port']; ?>" required>
        </div>
        <div class="form-group">
            <label for="smtp_username">SMTP Kullanıcı Adı:</label>
            <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="<?php echo $mail_settings['smtp_username']; ?>" required>
        </div>
        <div class="form-group">
            <label for="smtp_password">SMTP Şifre:</label>
            <input type="password" class="form-control" id="smtp_password" name="smtp_password" value="<?php echo $mail_settings['smtp_password']; ?>" required>
        </div>
        <div class="form-group">
            <label for="from_email">Gönderen Email:</label>
            <input type="email" class="form-control" id="from_email" name="from_email" value="<?php echo $mail_settings['from_email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="from_name">Gönderen Adı:</label>
            <input type="text" class="form-control" id="from_name" name="from_name" value="<?php echo $mail_settings['from_name']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Ayarları Güncelle</button>
    </form>
</div>

<?php include 'footer.php'; ?>
