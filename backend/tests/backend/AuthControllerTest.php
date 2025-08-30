<?php
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase {
    private $baseUrl = 'http://localhost:8000/api/auth/login';

    public function testValidLoginReturnsToken() {
        $data = json_encode([
            'username' => 'testuser',
            'password' => 'testpass'
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $data
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents($this->baseUrl, false, $context);
        $response = json_decode($result, true);

        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('user', $response);
    }

    public function testInvalidLoginReturns401() {
        $data = json_encode([
            'username' => 'wronguser',
            'password' => 'wrongpass'
        ]);
        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $data,
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents($this->baseUrl, false, $context);
        $response = json_decode($result, true);

        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Invalid username or password', $response['message']);
    }
}
