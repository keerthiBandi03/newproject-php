<?php
class Employee {
    public $id;
    public $userId;
    public $firstName;
    public $lastName;
    public $department;
    public $position;

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByUserId($userId) {
        $query = "SELECT * FROM employees WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = (int)$row['id'];
            $this->userId = (int)$row['user_id'];
            $this->firstName = $row['first_name'];
            $this->lastName = $row['last_name'];
            $this->department = $row['department'];
            $this->position = $row['position'];
            return $this;
        }
        return null;
    }
}
