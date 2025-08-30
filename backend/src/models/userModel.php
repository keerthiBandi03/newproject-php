
<?php
class UserModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM tblemployee WHERE USERNAME = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error finding user: " . $e->getMessage());
        }
    }
    
    public function findById($id) {
        try {
            $sql = "SELECT * FROM tblemployee WHERE EMPID = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error finding user by ID: " . $e->getMessage());
        }
    }
    
    public function updateLastLogin($userId) {
        try {
            $sql = "UPDATE tblemployee SET last_login = NOW() WHERE EMPID = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $userId);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating last login: " . $e->getMessage());
        }
    }
}
?>
