<?php
require_once '../config/Database.php';
require_once '../models/User.php';

class Auth {
    private $user;
    
    public function __construct() {
        $database = new Database();
        $this->user = new User($database->getConnection());
    }

    function isSafeRedirect(string $url): bool {
        if (strpos($url, '/') !== 0) {
            return false;
        }
    
        if (preg_match('#^//|\\\#', $url)) {
            return false;
        }
    
        return true;
    }

    // Register a new user
    public function register(array $data): array {
        // Validate required fields
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        // Validate password confirmation
        if ($data['password'] !== $data['password_confirm']) {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Check if email or username already exists
        if ($this->user->findByEmail($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        if ($this->user->findByUsername($data['username'])) {
            return ['success' => false, 'message' => 'Username already taken'];
        }

        // Create user
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'profile_description' => $data['profile_description'] ?? '',
            'profile_picture_url' => $data['profile_picture_url'] ?? '',
            'social_links' => $data['social_links'] ?? [],
            'role' => $data['role'] ?? 'user',  // Changed from 0 to 'user'
            'status' => $data['status'] ?? 'active'  // Changed from 1 to 'active'
        ];

        $userId = $this->user->create($userData);
        if ($userId) {
            return [
                'success' => true,
                'message' => 'Registration successful',
                'user_id' => $userId
            ];
        }

        return ['success' => false, 'message' => 'Registration failed'];
    }

    // Login a user
    public function login(string $email, string $password): array {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        $userData = $this->user->findByEmail($email);

        if (!$userData || !password_verify($password, $userData['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }

        // Check if user is active
        if ($userData['status'] !== 'active') {
            return ['success' => false, 'message' => 'Account is not active'];
        }

        return [
            'success' => true,
            'user_id' => $userData['id'],
            'username' => $userData['username'],
            'role' => $userData['role'],
            'message' => 'Login successful'
        ];
    }

    // Create user session
    public function createSession(string $user_id, string $username, string $role): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $_SESSION = [
            'user_id' => $user_id,
            'username' => $username,
            'role' => $role,
            'logged_in' => true
        ];
        
        session_regenerate_id(true);
    }

    public function verifySession(): bool {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }

    public function getUserInfo(int $id): array {
        $result = $this->user->getById($id);
        if($result){
            return ['success' => true, 'data' => $result];
        }
        return ['success' => false, 'message' => 'User not found'];
    }

    public function getCurrentUser(): ?array {
        if (!$this->verifySession()) {
            return null;
        }

        return $this->user->getById($_SESSION['user_id']);
    }

    // Check if current user has specific role
    public function hasRole(string $role): bool {
        $user = $this->getCurrentUser();
        return $user && $user['role'] === $role;
    }

    // Check if current user is logged in
    public function isLoggedIn(): bool {
        return $this->verifySession();
    }
}
?>