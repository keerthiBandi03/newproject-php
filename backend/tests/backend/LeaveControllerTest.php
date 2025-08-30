<?php
use PHPUnit\Framework\TestCase;

class LeaveControllerTest extends TestCase {
    private $baseUrl = 'http://localhost:8000/api/leaves';
    private $jwtToken;

    protected function setUp(): void {
        // Use valid credentials to get token
        $authUrl = 'http://localhost:8000/api/auth/login';
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
        $result = file_get_contents($authUrl, false, $context);
        $response = json_decode($result, true);
        $this->jwtToken = $response['token'] ?? '';
    }

    public function testCreateLeave() {
        $data = json_encode([
            'startDate' => '2024-07-01',
            'endDate' => '2024-07-05',
            'type' => 'vacation',
            'reason' => 'Family trip'
        ]);
        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\nAuthorization: Bearer {$this->jwtToken}\r\n",
                'content' => $data
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents($this->baseUrl, false, $context);
        $response = json_decode($result, true);

        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Leave request submitted successfully', $response['message']);
    }

    public function testListLeaves() {
        $opts = [
            'http' => [
                'method'  => 'GET',
                'header'  => "Authorization: Bearer {$this->jwtToken}\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents($this->baseUrl, false, $context);
        $response = json_decode($result, true);

        $this->assertIsArray($response);
    }

    public function testUpdateLeaveStatusForbiddenIfNotManager() {
        // Assuming testuser is not manager
        $leaveId = 1;
        $data = json_encode(['status' => 'approved']);
        $opts = [
            'http' => [
                'method'  => 'PUT',
                'header'  => "Content-Type: application/json\r\nAuthorization: Bearer {$this->jwtToken}\r\n",
                'content' => $data,
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($opts);
        $url = $this->baseUrl . "/{$leaveId}/status";
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result, true);

        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Forbidden: requires manager role', $response['message']);
    }
}
