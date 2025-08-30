<?php
require_once __DIR__ . '/../models/Leave.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';
require_once __DIR__ . '/../utils/request.php';

class LeaveController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listLeaves() {
        auth_middleware();

        $userId = $GLOBALS['user']['sub'] ?? null;
        if (!$userId) {
            sendJsonResponse(['message' => 'Unauthorized'], 401);
            return;
        }

        // Get employeeId from userId
        $employeeModel = new Employee($this->db);
        $employee = $employeeModel->findByUserId($userId);
        if (!$employee) {
            sendJsonResponse(['message' => 'Employee not found'], 404);
            return;
        }

        $leaveModel = new Leave($this->db);
        $leaves = $leaveModel->findByEmployeeId($employee->id);

        sendJsonResponse($leaves);
    }

    public function createLeave() {
        auth_middleware();

        $userId = $GLOBALS['user']['sub'] ?? null;
        if (!$userId) {
            sendJsonResponse(['message' => 'Unauthorized'], 401);
            return;
        }

        $data = getJsonInput();

        // Validate required fields
        $requiredFields = ['startDate', 'endDate', 'type', 'reason'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                sendJsonResponse(['message' => "Field '$field' is required"], 400);
                return;
            }
        }

        $employeeModel = new Employee($this->db);
        $employee = $employeeModel->findByUserId($userId);
        if (!$employee) {
            sendJsonResponse(['message' => 'Employee not found'], 404);
            return;
        }

        $leaveModel = new Leave($this->db);
        $success = $leaveModel->createLeave([
            'employeeId' => $employee->id,
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'type' => $data['type'],
            'reason' => $data['reason'],
        ]);

        if ($success) {
            sendJsonResponse(['message' => 'Leave request submitted successfully']);
        } else {
            sendJsonResponse(['message' => 'Failed to submit leave request'], 500);
        }
    }

    public function updateLeaveStatus($id) {
        auth_middleware();

        $userRoles = $GLOBALS['user']['roles'] ?? [];
        if (!in_array('manager', $userRoles)) {
            sendJsonResponse(['message' => 'Forbidden: requires manager role'], 403);
            return;
        }

        $data = getJsonInput();
        if (empty($data['status']) || !in_array($data['status'], ['approved', 'rejected'])) {
            sendJsonResponse(['message' => 'Invalid or missing status'], 400);
            return;
        }

        $leaveModel = new Leave($this->db);
        $success = $leaveModel->updateStatus($id, $data['status']);

        if ($success) {
            sendJsonResponse(['message' => 'Leave status updated successfully']);
        } else {
            sendJsonResponse(['message' => 'Failed to update leave status'], 500);
        }
    }
}
