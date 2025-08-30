<?php
require_once __DIR__ . '/../controllers/LeaveController.php';

$leaveController = new LeaveController();

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && preg_match('#^/api/leaves$#', $uri)) {
    $leaveController->listLeaves();
    exit();
}

if ($method === 'POST' && preg_match('#^/api/leaves$#', $uri)) {
    $leaveController->createLeave();
    exit();
}

if ($method === 'PUT' && preg_match('#^/api/leaves/(\d+)/status$#', $uri, $matches)) {
    $id = (int)$matches[1];
    $leaveController->updateLeaveStatus($id);
    exit();
}
