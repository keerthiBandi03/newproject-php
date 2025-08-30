
<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/LeaveController.php';
require_once __DIR__ . '/controllers/EmployeeController.php';
require_once __DIR__ . '/middleware/auth_middleware.php';

header('Content-Type: application/json');

// Handle CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: ' . CORS_ORIGINS);
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit();
}

header('Access-Control-Allow-Origin: ' . CORS_ORIGINS);
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Authenticate user
$user = authenticate();
if (!$user) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $leaveController = new LeaveController();
    $employeeController = new EmployeeController();
    
    // Get dashboard statistics
    $leaveStats = $leaveController->getLeaveStatistics();
    $employeeProfile = $employeeController->getEmployeeProfile($user['sub']);
    
    echo json_encode([
        'employee' => $employeeProfile,
        'statistics' => $leaveStats,
        'recentLeaves' => $leaveController->getRecentLeaves(5)
    ]);
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>
