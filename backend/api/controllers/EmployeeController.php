<?php
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';

class EmployeeController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getProfile() {
        auth_middleware();

        $userId = $GLOBALS['user']['sub'] ?? null;
        if (!$userId) {
            sendJsonResponse(['message' => 'Unauthorized'], 401);
            return;
        }

        $employeeModel = new Employee($this->db);
        $employee = $employeeModel->findByUserId($userId);
        if (!$employee) {
            sendJsonResponse(['message' => 'Employee profile not found'], 404);
            return;
        }

        sendJsonResponse([
            'id' => $employee->id,
            'userId' => $employee->userId,
            'firstName' => $employee->firstName,
            'lastName' => $employee->lastName,
            'department' => $employee->department,
            'position' => $employee->position
        ]);
    }
}
