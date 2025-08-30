<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_jwt(array $payload, string $secret, int $expiry = 3600): string {
    $issuedAt = time();
    $expire = $issuedAt + $expiry;
    $token = array_merge($payload, [
        'iat' => $issuedAt,
        'exp' => $expire
    ]);
    return JWT::encode($token, $secret, 'HS256');
}

function verify_jwt(string $token, string $secret) {
    try {
        $decoded = JWT::decode($token, new Key($secret, 'HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        return false;
    }
}
