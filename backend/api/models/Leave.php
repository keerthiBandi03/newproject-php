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
