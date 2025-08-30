<?php
class User {
    public $id;
    public $username;
    public $email;
    public $passwordHash;
    public $roles = [];

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = (int)$row['id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->passwordHash = $row['password_hash'];
            $this->roles = explode(',', $row['roles']);
            return $this;
        }
        return null;
    }

    public function verifyPassword($password): bool {
        return password_verify($password, $this->passwordHash);
    }
}
