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
<?php

class User {
    public $id;
    public $username;
    public $email;
    public $password;
    public $roles;
    
    private $conn;
    private $table_name = "tblemployee";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function findByUsername($username) {
        $query = "SELECT EMPID, USERNAME, EMPNAME, EMPPOSITION, PASSWRD FROM " . $this->table_name . " WHERE USERNAME = ? AND ACCSTATUS = 'YES'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $user = new User($this->conn);
            $user->id = $row['EMPID'];
            $user->username = $row['USERNAME'];
            $user->email = $row['USERNAME']; // Using username as email
            $user->password = $row['PASSWRD'];
            
            // Determine roles based on position
            $position = $row['EMPPOSITION'];
            if (stripos($position, 'administrator') !== false) {
                $user->roles = ['admin', 'manager'];
            } elseif (stripos($position, 'manager') !== false) {
                $user->roles = ['manager'];
            } elseif (stripos($position, 'supervisor') !== false) {
                $user->roles = ['supervisor'];
            } else {
                $user->roles = ['employee'];
            }
            
            return $user;
        }
        
        return null;
    }
    
    public function verifyPassword($password) {
        // Check both SHA1 (existing) and plain text for compatibility
        return $this->password === sha1($password) || $this->password === $password;
    }
}
