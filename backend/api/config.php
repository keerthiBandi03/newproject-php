
<?php
// Main configuration file
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'leavedb');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'your_jwt_secret_key_here');

// API Configuration
define('API_VERSION', 'v1');
define('CORS_ORIGINS', getenv('CORS_ALLOWED_ORIGINS') ?: 'http://localhost:4200');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('UTC');
?>
