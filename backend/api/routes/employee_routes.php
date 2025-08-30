<?php
require_once __DIR__ . '/../controllers/EmployeeController.php';

$employeeController = new EmployeeController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('#^/api/employee/me$#', $_SERVER['REQUEST_URI'])) {
    $employeeController->getProfile();
    exit();
}
