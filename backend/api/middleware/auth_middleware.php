<?php
require_once __DIR__ . '/../helpers/jwt_helper.php';

function auth_middleware() {
    $headers = getallheaders();
    if (!isset($headers['Authorization']) && !isset($headers['authorization'])) {
        http_response_code(401);
        echo json_encode(['message' => 'Authorization header missing']);
        exit();
    }
    $authHeader = $headers['Authorization'] ?? $headers['authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid Authorization header format']);
        exit();
    }

    $secret = getenv('JWT_SECRET') ?: 'your_jwt_secret';

    $payload = verify_jwt($token, $secret);

    if ($payload === false) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
        exit();
    }
    $GLOBALS['user'] = $payload;
}
