<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/auth.php'; // Adjust path if needed

class AuthControllerTest extends TestCase
{
    private $auth;

    protected function setUp(): void
    {
        $this->auth = new AuthController();
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = []; // Reset session
    }

    public function testGuestCannotAccessProtectedPages()
    {
        $this->assertFalse($this->auth->isLoggedIn());
    }

    public function testUserLoginCreatesSession()
    {
        // Fake a successful login (you could also mock User class)
        $this->auth->createSession('1', 'testuser', 'user');

        $this->assertTrue($this->auth->isLoggedIn());
        $this->assertEquals('testuser', $_SESSION['username']);
        $this->assertEquals('user', $_SESSION['role']);
    }

    public function testUserRoleChecking()
    {
        $this->auth->createSession('2', 'adminuser', 'admin');

        $this->assertTrue($this->auth->hasRole('admin'));
        $this->assertFalse($this->auth->hasRole('user'));
    }

    public function testLogoutClearsSession()
    {
        $this->auth->createSession('3', 'someuser', 'user');
        $this->assertTrue($this->auth->isLoggedIn());

        $this->auth->logout();
        $this->assertFalse($this->auth->isLoggedIn());
    }
}
