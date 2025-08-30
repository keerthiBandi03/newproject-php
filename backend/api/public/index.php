<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/../middleware/cors_middleware.php';
cors_middleware();

require_once __DIR__ . '/../routes/routes.php';

http_response_code(404);
echo json_encode(['message' => 'Route not found']);
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable CORS
require_once __DIR__ . '/../middleware/cors_middleware.php';
cors_middleware();

// Load Composer autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Load environment variables
if (class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();
}

// Include routes
require_once __DIR__ . '/../routes/routes.php';

// Handle 404 for unmatched routes
http_response_code(404);
header('Content-Type: application/json');
echo json_encode(['message' => 'Endpoint not found']);
