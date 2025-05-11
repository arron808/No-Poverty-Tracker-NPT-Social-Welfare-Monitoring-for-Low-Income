<?php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllUsers() {
        $stmt = $this->conn->prepare("CALL GetAllUsers()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $password) {
        $stmt = $this->conn->prepare("CALL AddUser(:username, :password_hash, '')");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->execute();
    }

    public function updateUser($id, $username, $password) {
        if (!empty($password)) {
            $stmt = $this->conn->prepare("CALL UpdateUser(:id, :username, :password_hash)");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
        } else {
            $stmt = $this->conn->prepare("CALL UpdateUserNoPass(:id, :username)");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);
        }
        $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("CALL DeleteUser(:id)");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
