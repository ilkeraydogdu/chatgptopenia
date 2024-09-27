<?php

class ReportController
{
    // Report modelini bağlayalım
    protected $reportModel;

    public function __construct()
    {
        // Report modelini kullanmak için örneğini oluşturalım
        $this->reportModel = new Report();
    }

    /**
     * Kullanıcı raporlarını görüntüleme işlemi
     */
    public function userReports()
    {
        try {
            // Kullanıcı raporlarını modelden alalım
            $userReports = $this->reportModel->getUserReports();

            // View'e raporları gönderelim
            require_once '../app/views/report_user.php';
        } catch (Exception $e) {
            // Hata varsa hata mesajını göster
            echo 'Kullanıcı raporları getirilemedi: ' . $e->getMessage();
        }
    }

    /**
     * Ürün raporlarını görüntüleme işlemi
     */
    public function productReports()
    {
        try {
            // Ürün raporlarını modelden alalım
            $productReports = $this->reportModel->getProductReports();

            // View'e raporları gönderelim
            require_once '../app/views/report_product.php';
        } catch (Exception $e) {
            // Hata varsa hata mesajını göster
            echo 'Ürün raporları getirilemedi: ' . $e->getMessage();
        }
    }

    /**
     * Satış trendi raporlarını görüntüleme işlemi
     */
    public function salesTrendReports()
    {
        try {
            // Satış trend raporlarını modelden alalım
            $salesReports = $this->reportModel->getSalesTrendReports();

            // View'e satış raporlarını gönderelim
            require_once '../app/views/report_sales.php';
        } catch (Exception $e) {
            // Hata varsa hata mesajını göster
            echo 'Satış trend raporları getirilemedi: ' . $e->getMessage();
        }
    }

    /**
     * Raporları PDF olarak indirme işlemi
     */
    public function downloadReport($reportId)
    {
        try {
            // İlgili raporu modelden alalım
            $report = $this->reportModel->getReportById($reportId);

            if ($report) {
                // Raporu PDF olarak oluşturma işlemi
                $this->generatePDF($report);
            } else {
                echo 'Rapor bulunamadı.';
            }
        } catch (Exception $e) {
            // Hata varsa hata mesajını göster
            echo 'Rapor indirme işlemi başarısız: ' . $e->getMessage();
        }
    }

    /**
     * PDF oluşturma işlemi
     */
    private function generatePDF($report)
    {
        // FPDF veya benzeri bir kütüphane kullanarak PDF oluşturma
        require_once '../libs/fpdf/fpdf.php'; // FPDF kütüphanesini ekliyoruz

        // Yeni bir PDF oluştur
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // Rapor başlığını ekle
        $pdf->Cell(40, 10, 'Rapor Başlığı: ' . $report['title']);
        $pdf->Ln();
        
        // Rapor içeriğini ekle
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, 'Rapor İçeriği: ' . $report['content']);
        
        // PDF çıktısını oluştur
        $pdf->Output('D', 'rapor_' . $report['id'] . '.pdf');  // D: İndir, S: Ekrana göster
    }
}
