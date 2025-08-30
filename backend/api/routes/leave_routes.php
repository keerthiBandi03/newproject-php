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
<?php
require_once __DIR__ . '/../controllers/LeaveController.php';

$leaveController = new LeaveController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('#^/api/leaves$#', $_SERVER['REQUEST_URI'])) {
    $leaveController->listLeaves();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('#^/api/leaves$#', $_SERVER['REQUEST_URI'])) {
    $leaveController->createLeave();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('#^/api/leaves/(\d+)/status$#', $_SERVER['REQUEST_URI'], $matches)) {
    $leaveController->updateLeaveStatus($matches[1]);
    exit();
}
