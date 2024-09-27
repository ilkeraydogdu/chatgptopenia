<?php

class StoreController
{
    private $storeModel;

    public function __construct()
    {
        // StoreModel ile bağlantı kuruyoruz
        $this->storeModel = new Store();
    }

    // Mağaza oluşturma işlemi
    public function createStore($storeData)
    {
        // Gelen mağaza verilerini doğruluyoruz
        if ($this->validateStoreData($storeData)) {
            // Mağaza modeline kaydetme işlemi
            $storeId = $this->storeModel->create($storeData);

            if ($storeId) {
                return ['status' => 'success', 'message' => 'Mağaza başarıyla oluşturuldu.'];
            } else {
                return ['status' => 'error', 'message' => 'Mağaza oluşturulamadı.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Geçersiz mağaza bilgileri.'];
        }
    }

    // Mağaza düzenleme işlemi
    public function updateStore($storeId, $storeData)
    {
        // Gelen mağaza verilerini doğruluyoruz
        if ($this->validateStoreData($storeData)) {
            // Mağaza modelinde güncelleme işlemi
            $isUpdated = $this->storeModel->update($storeId, $storeData);

            if ($isUpdated) {
                return ['status' => 'success', 'message' => 'Mağaza başarıyla güncellendi.'];
            } else {
                return ['status' => 'error', 'message' => 'Mağaza güncellenemedi.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Geçersiz mağaza bilgileri.'];
        }
    }

    // Mağaza silme işlemi
    public function deleteStore($storeId)
    {
        $isDeleted = $this->storeModel->delete($storeId);

        if ($isDeleted) {
            return ['status' => 'success', 'message' => 'Mağaza başarıyla silindi.'];
        } else {
            return ['status' => 'error', 'message' => 'Mağaza silinemedi.'];
        }
    }

    // Mağaza bilgilerini görüntüleme işlemi
    public function getStore($storeId)
    {
        return $this->storeModel->findById($storeId);
    }

    // Tüm mağazaları listeleme işlemi
    public function getAllStores()
    {
        return $this->storeModel->findAll();
    }

    // Mağaza bilgilerini doğrulayan fonksiyon
    private function validateStoreData($storeData)
    {
        if (isset($storeData['name']) && isset($storeData['address']) && isset($storeData['owner'])) {
            // İhtiyaç duyulan verilerin kontrolü
            return true;
        }
        return false;
    }
}

?>
