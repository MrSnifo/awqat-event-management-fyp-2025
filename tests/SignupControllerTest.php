<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/auth.php';

class SignupControllerTest extends TestCase
{
    private $authController;

    protected function setUp(): void
    {
        $this->authController = new AuthController();
    }

    public function testSuccessfulSignup()
    {
        $data = [
            'username' => 'testuser' . rand(1, 9999),
            'email' => 'test' . rand(1, 9999) . '@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
        ];

        $result = $this->authController->register($data);

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }

    public function testSignupWithMissingFields()
    {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'password_confirm' => '',
        ];

        $result = $this->authController->register($data);

        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
    }

    public function testSignupWithInvalidEmail()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
        ];

        $result = $this->authController->register($data);

        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
    }

    public function testSignupWithPasswordMismatch()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'DifferentPassword123!',
        ];

        $result = $this->authController->register($data);

        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
    }
}
?>
