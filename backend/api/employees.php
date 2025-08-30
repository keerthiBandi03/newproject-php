
<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/EmployeeController.php';
require_once __DIR__ . '/middleware/auth_middleware.php';

header('Content-Type: application/json');

// Handle CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: ' . CORS_ORIGINS);
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit();
}

header('Access-Control-Allow-Origin: ' . CORS_ORIGINS);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Authenticate user for protected endpoints
$user = authenticate();
if (!$user) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

$employeeController = new EmployeeController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'me') {
            $employeeController->getProfile();
        } else {
            $employeeController->getAllEmployees();
        }
        break;
    case 'POST':
        $employeeController->createEmployee();
        break;
    case 'PUT':
        $employeeController->updateEmployee();
        break;
    case 'DELETE':
        $employeeController->deleteEmployee();
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
}
?>
