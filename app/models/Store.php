<?php

class Store
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Mağaza bilgilerini getirme
    public function getStoreInfo($storeId)
    {
        $query = "SELECT * FROM stores WHERE id = :storeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mağaza ekleme
    public function addStore($storeData)
    {
        $query = "INSERT INTO stores (name, owner_id, commission_rate, status, created_at) 
        VALUES (:name, :ownerId, :commissionRate, :status, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $storeData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':ownerId', $storeData['owner_id'], PDO::PARAM_INT);
        $stmt->bindParam(':commissionRate', $storeData['commission_rate'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $storeData['status'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Mağaza güncelleme
    public function updateStore($storeId, $storeData)
    {
        $query = "UPDATE stores SET 
        name = :name, 
        commission_rate = :commissionRate, 
        status = :status 
        WHERE id = :storeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $storeData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':commissionRate', $storeData['commission_rate'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $storeData['status'], PDO::PARAM_INT);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Mağaza silme
    public function deleteStore($storeId)
    {
        $query = "DELETE FROM stores WHERE id = :storeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Tüm mağazaları getirme
    public function getAllStores()
    {
        $query = "SELECT * FROM stores";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mağaza ürün ekleme
    public function addProductToStore($storeId, $productData)
    {
        $query = "INSERT INTO products (store_id, name, price, stock, status, created_at) 
        VALUES (:storeId, :name, :price, :stock, :status, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $productData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':price', $productData['price'], PDO::PARAM_STR);
        $stmt->bindParam(':stock', $productData['stock'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $productData['status'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Mağazaya ait tüm ürünleri getirme
    public function getProductsByStore($storeId)
    {
        $query = "SELECT * FROM products WHERE store_id = :storeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mağaza komisyon oranını güncelleme
    public function updateCommissionRate($storeId, $commissionRate)
    {
        $query = "UPDATE stores SET commission_rate = :commissionRate WHERE id = :storeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':commissionRate', $commissionRate, PDO::PARAM_STR);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

