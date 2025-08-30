
<?php
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../utils/db.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $database = new Database();
        $this->userModel = new UserModel($database->getConnection());
    }
    
    public function login($username, $password) {
        try {
            $user = $this->userModel->findByUsername($username);
            
            if (!$user || !$this->verifyPassword($password, $user['PASSWRD'])) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }
            
            if ($user['ACCSTATUS'] !== 'YES') {
                return ['success' => false, 'message' => 'Account is disabled'];
            }
            
            // Generate JWT token
            $payload = [
                'user_id' => $user['EMPID'],
                'username' => $user['USERNAME'],
                'exp' => time() + (24 * 60 * 60) // 24 hours
            ];
            
            $token = $this->generateJWT($payload);
            
            return [
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user['EMPID'],
                    'username' => $user['USERNAME'],
                    'name' => $user['EMPNAME'],
                    'position' => $user['EMPPOSITION']
                ]
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Login failed'];
        }
    }
    
    private function verifyPassword($password, $hashedPassword) {
        // Check SHA1 hash (existing format)
        return sha1($password) === $hashedPassword;
    }
    
    private function generateJWT($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $headerEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $payloadEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, JWT_SECRET, true);
        $signatureEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }
}
?>
