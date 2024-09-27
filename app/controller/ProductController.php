<?php

class ProductController
{
    private $productModel;

    public function __construct()
    {
        // Modeli yükle
        $this->productModel = new Product();
    }

    // Ürünleri listeleme
    public function index()
    {
        $products = $this->productModel->getAllProducts();
        require_once '../app/views/product_list.php';
    }

    // Ürün detayları
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        require_once '../app/views/product_details.php';
    }

    // Ürün ekleme formu
    public function create()
    {
        require_once '../app/views/product_add.php';
    }

    // Ürün ekleme işlemi
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'image' => $this->uploadImage($_FILES['image'])  // Görsel yükleme işlemi
            ];

            if ($this->productModel->addProduct($data)) {
                header('Location: /product/index');
            } else {
                die('Ürün eklenirken bir hata oluştu');
            }
        } else {
            $this->create();
        }
    }

    // Ürün düzenleme formu
    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        require_once '../app/views/product_edit.php';
    }

    // Ürün güncelleme işlemi
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'image' => !empty($_FILES['image']['name']) ? $this->uploadImage($_FILES['image']) : $_POST['existing_image']
            ];

            if ($this->productModel->updateProduct($id, $data)) {
                header('Location: /product/show/' . $id);
            } else {
                die('Ürün güncellenirken bir hata oluştu');
            }
        } else {
            $this->edit($id);
        }
    }

    // Ürün silme işlemi
    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /product/index');
        } else {
            die('Ürün silinirken bir hata oluştu');
        }
    }

    // Görsel yükleme işlemi
    private function uploadImage($file)
    {
        $targetDir = "../public/assets/images/";
        $targetFile = $targetDir . basename($file['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Dosya türü kontrolü
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            die('Yalnızca görüntü dosyaları kabul edilir.');
        }

        // Dosya zaten var mı kontrolü
        if (file_exists($targetFile)) {
            die('Bu dosya zaten mevcut.');
        }

        // Dosya boyutu kontrolü
        if ($file['size'] > 5000000) {
            die('Dosya çok büyük.');
        }

        // Dosya türü kontrolü
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            die('Sadece JPG, JPEG ve PNG dosyaları kabul edilir.');
        }

        // Dosyayı yükleme
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return basename($file['name']);
        } else {
            die('Görsel yükleme sırasında bir hata oluştu.');
        }
    }
}
