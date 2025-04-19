<<<<<<< HEAD
<?php
require_once '../config/Database.php';
require_once '../models/User.php';


class Auth {
    private $user;
    
    public function __construct() {
        $database = new Database();
        $this->user = new User($database->getConnection());
    }

    // Register a new user
    public function register(mixed $data) {
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        if ($data['password'] !== $data['password_confirm']) {
            return ['success' => false, 'message' => 'Passwords do not match'];
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
            'profile_description' => '',
            'profile_picture_url' => '',
            'social_links' => [],
            'role' => 0,
            'status' => 1
        ];

        if ($this->user->create($userData)) {
            return ['success' => true, 'message' => 'Registration successful'];
        }

        return ['success' => false, 'message' => 'Registration failed'];
    }

    // Login a user
    public function login(string $email, string $password) {
        $userData = $this->user->findByEmail($email);

        if (!$userData || !password_verify($password, $userData['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }

        return [
            'success' => true,
            'user_id' => $userData['id'],
            'username' => $userData['username'],
            'message' => 'Login successful'
        ];
    }

    // Create user session
    public function createSession(string $user_id, string $user_name) {
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $user_name;
        $_SESSION['logged_in'] = true;
        session_regenerate_id(true);
    }

    public function verifySession() {
        session_start();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
    }

    public function getCurrentUser() {
        if (!$this->verifySession()) {
            return null;
        }

        $user_id = $_SESSION['user_id'];
        return $this->user->getById($user_id);
    }
}
?>
=======

>>>>>>> d3c286e63cc3532386efb77333fadbd58b4f2c3b
