<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';
require_once __DIR__ . '/../utils/request.php';

class AuthController {
    private $db;
    private $secret;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->secret = getenv('JWT_SECRET') ?: 'your_jwt_secret';
    }

    public function login() {
        $data = getJsonInput();
        if (!isset($data['username']) || !isset($data['password'])) {
            sendJsonResponse(['message' => 'Username and password required'], 400);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findByUsername($data['username']);
        if (!$user || !$user->verifyPassword($data['password'])) {
            sendJsonResponse(['message' => 'Invalid username or password'], 401);
            return;
        }

        $payload = [
            'sub' => $user->id,
            'username' => $user->username,
            'roles' => $user->roles
        ];

        $token = generate_jwt($payload, $this->secret, 3600);

        sendJsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->roles
            ]
        ]);
    }
}
