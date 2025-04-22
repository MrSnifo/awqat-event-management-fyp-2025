<?php
require_once '../config/Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user - corrected version
public function create(array $data): int|false {
    $query = "INSERT INTO {$this->table} 
             (username, email, password_hash, profile_description, 
              profile_picture_url, social_links, role, status)
             VALUES 
             (:username, :email, :password_hash, :profile_description, 
              :profile_picture_url, :social_links, :role, :status)";

    $stmt = $this->conn->prepare($query);

    // Store values in variables first
    $username = $data['username'];
    $email = $data['email'];
    $password_hash = $data['password_hash'];
    $profile_description = $data['profile_description'] ?? null;
    $profile_picture_url = $data['profile_picture_url'] ?? null;
    $socialLinksJson = json_encode($data['social_links'] ?? []);
    $role = $data['role'] ?? 'user';
    $status = $data['status'] ?? 'active';

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password_hash', $password_hash);
    $stmt->bindParam(':profile_description', $profile_description, PDO::PARAM_NULL);
    $stmt->bindParam(':profile_picture_url', $profile_picture_url, PDO::PARAM_NULL);
    $stmt->bindParam(':social_links', $socialLinksJson);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        return $this->conn->lastInsertId();
    }

    return false;
}

// Update user by ID - corrected version


    // Get user by ID
    public function getById(int $id): array|false {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user by ID
    public function update(int $id, array $data): bool {
        $query = "UPDATE {$this->table} 
                 SET username = :username, 
                     email = :email, 
                     password_hash = :password_hash, 
                     profile_description = :profile_description, 
                     profile_picture_url = :profile_picture_url, 
                     social_links = :social_links, 
                     role = :role, 
                     status = :status,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        // Store values in variables first
        $username = $data['username'];
        $email = $data['email'];
        $password_hash = $data['password_hash'];
        $profile_description = $data['profile_description'] ?? null;
        $profile_picture_url = $data['profile_picture_url'] ?? null;
        $socialLinksJson = json_encode($data['social_links'] ?? []);
        $role = $data['role'] ?? 'user';
        $status = $data['status'] ?? 'active';
    
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':profile_description', $profile_description, PDO::PARAM_NULL);
        $stmt->bindParam(':profile_picture_url', $profile_picture_url, PDO::PARAM_NULL);
        $stmt->bindParam(':social_links', $socialLinksJson);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
    
        return $stmt->execute();
    }
    // Delete a user by ID
    public function delete(int $id): bool {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Find user by email
    public function findByEmail(string $email): array|false {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Find user by username
    public function findByUsername(string $username): array|false {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all users with optional filtering
    public function getAll(string | null $role, string | null $status): array {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if ($role !== null) {
            $query .= " AND role = :role";
            $params[':role'] = $role;
        }

        if ($status !== null) {
            $query .= " AND status = :status";
            $params[':status'] = $status;
        }

        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user status
    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    // Update user role
    public function updateRole(int $id, string $role): bool {
        $query = "UPDATE {$this->table} SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }
}