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
<?php

class Employee {
    public $id;
    public $userId;
    public $firstName;
    public $lastName;
    public $department;
    public $position;
    public $company;
    public $employeeId;
    public $availableLeave;
    
    private $conn;
    private $table_name = "tblemployee";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function findByUserId($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE EMPID = ? AND ACCSTATUS = 'YES'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $employee = new Employee($this->conn);
            $employee->id = $row['EMPID'];
            $employee->userId = $row['EMPID'];
            
            // Split full name into first and last name
            $nameParts = explode(' ', $row['EMPNAME'], 2);
            $employee->firstName = $nameParts[0] ?? '';
            $employee->lastName = $nameParts[1] ?? '';
            
            $employee->department = $row['DEPARTMENT'];
            $employee->position = $row['EMPPOSITION'];
            $employee->company = $row['COMPANY'];
            $employee->employeeId = $row['EMPLOYID'];
            $employee->availableLeave = $row['AVELEAVE'];
            
            return $employee;
        }
        
        return null;
    }
    
    public function findByEmployeeId($employeeId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE EMPLOYID = ? AND ACCSTATUS = 'YES'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $employeeId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $employee = new Employee($this->conn);
            $employee->id = $row['EMPID'];
            $employee->userId = $row['EMPID'];
            
            $nameParts = explode(' ', $row['EMPNAME'], 2);
            $employee->firstName = $nameParts[0] ?? '';
            $employee->lastName = $nameParts[1] ?? '';
            
            $employee->department = $row['DEPARTMENT'];
            $employee->position = $row['EMPPOSITION'];
            $employee->company = $row['COMPANY'];
            $employee->employeeId = $row['EMPLOYID'];
            $employee->availableLeave = $row['AVELEAVE'];
            
            return $employee;
        }
        
        return null;
    }
}
