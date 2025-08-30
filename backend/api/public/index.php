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
