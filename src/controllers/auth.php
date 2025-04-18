<?php
class Auth {
    private $db;
    private $sessionName = 'SECURE_SESSION';

    public function __construct() {
        $this->db = Database::getInstance();
        $this->initSession();
    }

    private function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'name' => $this->sessionName,
                'cookie_lifetime' => 86400, // 1 day
                'cookie_secure' => true,
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict',
                'use_strict_mode' => true
            ]);
        }
    }

    // Verify login credentials
    public function verifyLogin($email, $password) {
        try {
            // Input validation
            if (empty($email) || empty($password)) {
                return ['success' => false, 'message' => 'Email and password are required'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Invalid email format'];
            }

            // Get user from database
            $stmt = $this->db->prepare("SELECT id, password_hash FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            return ['success' => true, 'user_id' => $user['id']];
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred'];
        }
    }

    // Create secure session with cookie
    public function createSession($userId) {
        try {
            // Generate tokens
            $sessionToken = bin2hex(random_bytes(32));
            $authToken = bin2hex(random_bytes(32));
            $expiresAt = time() + 86400; // 1 day

            // Store session in database
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions 
                (user_id, session_token, auth_token, expires_at) 
                VALUES (?, ?, ?, FROM_UNIXTIME(?))
            ");
            $stmt->execute([$userId, $sessionToken, $authToken, $expiresAt]);

            // Set session variables
            $_SESSION = [
                'user_id' => $userId,
                'auth_token' => $authToken,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
            ];

            // Set secure cookie
            setcookie(
                'session_token',
                $sessionToken,
                [
                    'expires' => $expiresAt,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            );

            return true;
        } catch (PDOException $e) {
            error_log("Session creation error: " . $e->getMessage());
            return false;
        }
    }

    // Verify session validity
    public function verifySession() {
        try {
            // Check if cookie exists
            if (empty($_COOKIE['session_token'])) {
                return false;
            }

            $sessionToken = $_COOKIE['session_token'];

            // Verify session in database
            $stmt = $this->db->prepare("
                SELECT user_id, auth_token 
                FROM user_sessions 
                WHERE session_token = ? 
                AND expires_at > NOW()
            ");
            $stmt->execute([$sessionToken]);
            $session = $stmt->fetch();

            if (!$session) {
                return false;
            }

            // Verify session matches
            if (empty($_SESSION['auth_token']) || 
                !hash_equals($_SESSION['auth_token'], $session['auth_token'])) {
                return false;
            }

            // Verify user agent consistency
            if ($_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
                return false;
            }

            return true;
        } catch (PDOException $e) {
            error_log("Session verification error: " . $e->getMessage());
            return false;
        }
    }

    // Destroy session
    public function logout() {
        try {
            if (!empty($_COOKIE['session_token'])) {
                // Remove session from database
                $stmt = $this->db->prepare("
                    DELETE FROM user_sessions 
                    WHERE session_token = ?
                ");
                $stmt->execute([$_COOKIE['session_token']]);
                
                // Remove cookie
                setcookie(
                    'session_token',
                    '',
                    [
                        'expires' => time() - 3600,
                        'path' => '/',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]
                );
            }

            // Clear session data
            $_SESSION = [];
            session_destroy();
            return true;
        } catch (PDOException $e) {
            error_log("Logout error: " . $e->getMessage());
            return false;
        }
    }
}