<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('#^/api/auth/login$#', $_SERVER['REQUEST_URI'])) {
    $authController->login();
    exit();
}
<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('#^/api/auth/login$#', $_SERVER['REQUEST_URI'])) {
    $authController->login();
    exit();
}
