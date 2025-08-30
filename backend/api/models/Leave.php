<?php
class Leave {
    public $id;
    public $employeeId;
    public $startDate;
    public $endDate;
    public $type;
    public $status;
    public $reason;

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByEmployeeId($employeeId) {
        $query = "SELECT * FROM leaves WHERE employee_id = :employee_id ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employee_id', $employeeId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createLeave(array $data) {
        $query = "INSERT INTO leaves (employee_id, start_date, end_date, type, status, reason) VALUES (:employee_id, :start_date, :end_date, :type, 'pending', :reason)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employee_id', $data['employeeId']);
        $stmt->bindParam(':start_date', $data['startDate']);
        $stmt->bindParam(':end_date', $data['endDate']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':reason', $data['reason']);
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE leaves SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
<?php

class Leave {
    private $conn;
    private $table_name = "tblleave";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function findByEmployeeId($employeeId) {
        // First get the EMPLOYID from tblemployee
        $empQuery = "SELECT EMPLOYID FROM tblemployee WHERE EMPID = ?";
        $empStmt = $this->conn->prepare($empQuery);
        $empStmt->bind_param("i", $employeeId);
        $empStmt->execute();
        $empResult = $empStmt->get_result();
        
        if ($empRow = $empResult->fetch_assoc()) {
            $employId = $empRow['EMPLOYID'];
            
            $query = "SELECT * FROM " . $this->table_name . " WHERE EMPLOYID = ? ORDER BY DATEPOSTED DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $employId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $leaves = [];
            while ($row = $result->fetch_assoc()) {
                $leaves[] = [
                    'id' => $row['LEAVEID'],
                    'employeeId' => $row['EMPLOYID'],
                    'startDate' => $row['DATESTART'],
                    'endDate' => $row['DATEEND'],
                    'numberOfDays' => $row['NODAYS'],
                    'shiftTime' => $row['SHIFTTIME'],
                    'type' => $row['TYPEOFLEAVE'],
                    'reason' => $row['REASON'],
                    'status' => strtolower($row['LEAVESTATUS']),
                    'adminRemarks' => $row['ADMINREMARKS'],
                    'datePosted' => $row['DATEPOSTED']
                ];
            }
            
            return $leaves;
        }
        
        return [];
    }
    
    public function createLeave($data) {
        // Get EMPLOYID from tblemployee
        $empQuery = "SELECT EMPLOYID FROM tblemployee WHERE EMPID = ?";
        $empStmt = $this->conn->prepare($empQuery);
        $empStmt->bind_param("i", $data['employeeId']);
        $empStmt->execute();
        $empResult = $empStmt->get_result();
        
        if ($empRow = $empResult->fetch_assoc()) {
            $employId = $empRow['EMPLOYID'];
            
            // Calculate number of days
            $startDate = new DateTime($data['startDate']);
            $endDate = new DateTime($data['endDate']);
            $interval = $startDate->diff($endDate);
            $numberOfDays = $interval->days + 1; // +1 to include both start and end dates
            
            $query = "INSERT INTO " . $this->table_name . " 
                     (EMPLOYID, DATESTART, DATEEND, NODAYS, SHIFTTIME, TYPEOFLEAVE, REASON, LEAVESTATUS, ADMINREMARKS, DATEPOSTED) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDING', 'N/A', CURDATE())";
            
            $stmt = $this->conn->prepare($query);
            $shiftTime = 'All Day'; // Default value
            $stmt->bind_param("sssdsss", 
                $employId,
                $data['startDate'],
                $data['endDate'],
                $numberOfDays,
                $shiftTime,
                $data['type'],
                $data['reason']
            );
            
            return $stmt->execute();
        }
        
        return false;
    }
    
    public function updateStatus($leaveId, $status) {
        $query = "UPDATE " . $this->table_name . " SET LEAVESTATUS = ? WHERE LEAVEID = ?";
        $stmt = $this->conn->prepare($query);
        $statusUpper = strtoupper($status);
        $stmt->bind_param("si", $statusUpper, $leaveId);
        
        return $stmt->execute();
    }
}
