<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Kullanıcı ekleme
    public function addUser($name, $email, $password, $role = 'user')
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    // Kullanıcı bilgilerini alma
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kullanıcıyı email ile alma
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kullanıcı giriş işlemi
    public function login($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    // Şifreyi güncelleme
    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kullanıcıyı güncelleme
    public function updateUser($id, $name, $email, $role)
    {
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kullanıcıyı silme
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Tüm kullanıcıları listeleme
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kullanıcı aktivasyon işlemi
    public function activateUser($id)
    {
        $sql = "UPDATE users SET status = 'active' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kullanıcıyı pasif yapma
    public function deactivateUser($id)
    {
        $sql = "UPDATE users SET status = 'inactive' WHERE id = :id";
        $stmt = $->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
