<?php
// Kullanıcı raporları sayfası
require_once 'app/models/Report.php'; // Rapor modeli dahil ediliyor
require_once 'app/models/User.php';   // Kullanıcı modeli dahil ediliyor

// Kullanıcıların raporlarını çekmek için gerekli kontrol işlemleri
class ReportUser {
    
    private $reportModel;
    private $userModel;

    public function __construct() {
        // Model sınıflarını başlatma
        $this->reportModel = new Report();
        $this->userModel = new User();
    }

    public function index() {
        // Kullanıcıların listesi
        $users = $this->userModel->getAllUsers();
        
        // Kullanıcıların raporlarını almak
        $userReports = [];
        foreach ($users as $user) {
            $userReports[] = $this->reportModel->getUserReport($user['id']);
        }

        // Verileri görünüme göndermek
        require_once 'app/views/report_user.php';
    }
}

// ReportUser sınıfını başlatma ve işlemleri gerçekleştirme
$controller = new ReportUser();
$controller->index();

?>
