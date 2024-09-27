<?php

class Product
{
	private $db;

	public function __construct()
	{
        // Veritabanı bağlantısını başlat
		$this->db = Database::getConnection();
	}

    // Ürün ekleme fonksiyonu
	public function addProduct($data)
	{
		$query = "INSERT INTO products (name, category, price, description, stock, status, created_at) 
		VALUES (:name, :category, :price, :description, :stock, :status, :created_at)";
		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':name', $data['name']);
		$stmt->bindValue(':category', $data['category']);
		$stmt->bindValue(':price', $data['price']);
		$stmt->bindValue(':description', $data['description']);
		$stmt->bindValue(':stock', $data['stock']);
		$stmt->bindValue(':status', $data['status']);
		$stmt->bindValue(':created_at', date('Y-m-d H:i:s'));

		return $stmt->execute();
	}

    // Ürün düzenleme fonksiyonu
	public function updateProduct($id, $data)
	{
		$query = "UPDATE products 
		SET name = :name, category = :category, price = :price, description = :description, 
		stock = :stock, status = :status, updated_at = :updated_at
		WHERE id = :id";
		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':name', $data['name']);
		$stmt->bindValue(':category', $data['category']);
		$stmt->bindValue(':price', $data['price']);
		$stmt->bindValue(':description', $data['description']);
		$stmt->bindValue(':stock', $data['stock']);
		$stmt->bindValue(':status', $data['status']);
		$stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));

		return $stmt->execute();
	}

    // Ürün silme fonksiyonu
	public function deleteProduct($id)
	{
		$query = "DELETE FROM products WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);

		return $stmt->execute();
	}

    // Ürün listeleme fonksiyonu
	public function getAllProducts()
	{
		$query = "SELECT * FROM products ORDER BY created_at DESC";
		$stmt = $this->db->query($query);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    // Tek ürün getirme fonksiyonu
	public function getProductById($id)
	{
		$query = "SELECT * FROM products WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    // Ürünleri kategorisine göre listeleme fonksiyonu
	public function getProductsByCategory($category)
	{
		$query = "SELECT * FROM products WHERE category = :category";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':category', $category);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    // Ürün aktif/pasif yapma fonksiyonu
	public function changeProductStatus($id, $status)
	{
		$query = "UPDATE products SET status = :status WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':status', $status);

		return $stmt->execute();
	}
}

?>
