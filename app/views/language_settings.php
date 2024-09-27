<?php
// Gerekli dosyalar ve oturum başlatma
session_start();
require_once '../app/config/database.php';
require_once '../app/models/Language.php';

// Dil seçeneklerini almak için model çağırma
$languageModel = new Language();
$languages = $languageModel->getLanguages();

// Eğer form gönderildiyse dil ayarlarını kaydet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedLanguage = $_POST['selected_language'];
    $languageModel->setLanguage($selectedLanguage);
    $_SESSION['message'] = "Dil ayarları başarıyla güncellendi.";
    header("Location: language_settings.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dil Ayarları</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Dil Ayarları</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert success">
                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form action="language_settings.php" method="POST">
            <label for="selected_language">Kullanılacak Dil</label>
            <select name="selected_language" id="selected_language">
                <?php foreach ($languages as $language): ?>
                    <option value="<?= $language['code']; ?>" <?= $language['is_active'] ? 'selected' : ''; ?>>
                        <?= $language['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Dil Ayarlarını Kaydet</button>
        </form>
    </div>
</body>
</html>
