<?php

class PageController
{
    private $pageModel;

    public function __construct()
    {
        // Page modelini çağırıyoruz
        $this->pageModel = new Page();
    }

    // Tüm sayfaları listeleme
    public function index()
    {
        $pages = $this->pageModel->getAllPages();
        require_once '../app/views/page_management.php'; // Sayfa yönetimi view dosyası
    }

    // Yeni sayfa ekleme
    public function addPage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini işleme
            $pageTitle = $_POST['title'];
            $pageContent = $_POST['content'];
            $seoKeywords = $_POST['seo_keywords'];
            $seoDescription = $_POST['seo_description'];

            // Yeni sayfayı veritabanına ekle
            $this->pageModel->addPage($pageTitle, $pageContent, $seoKeywords, $seoDescription);

            // Başarı mesajı ve yönlendirme
            header('Location: /admin/pages');
        } else {
            // Yeni sayfa formunu göster
            require_once '../app/views/add_page.php';
        }
    }

    // Sayfa düzenleme
    public function editPage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini işleme
            $pageTitle = $_POST['title'];
            $pageContent = $_POST['content'];
            $seoKeywords = $_POST['seo_keywords'];
            $seoDescription = $_POST['seo_description'];

            // Sayfayı güncelle
            $this->pageModel->updatePage($id, $pageTitle, $pageContent, $seoKeywords, $seoDescription);

            // Başarı mesajı ve yönlendirme
            header('Location: /admin/pages');
        } else {
            // Sayfa bilgilerini getir ve formu doldur
            $page = $this->pageModel->getPageById($id);
            require_once '../app/views/edit_page.php';
        }
    }

    // Sayfa silme
    public function deletePage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sayfayı sil
            $this->pageModel->deletePage($id);

            // Başarı mesajı ve yönlendirme
            header('Location: /admin/pages');
        }
    }
}

