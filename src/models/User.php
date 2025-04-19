<?php
class User
{
    private $db;
    private $table = 'users';

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new user.
    public function create(array $data): bool {
        $query = "INSERT INTO {$this->table} 
            (username, email, password_hash, profile_description, profile_picture_url, social_links, role, status)
            VALUES (:username, :email, :password_hash, :profile_description, :profile_picture_url, :social_links, :role, :status)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':username'             => $data['username'],
            ':email'                => $data['email'],
            ':password_hash'        => $data['password_hash'],
            ':profile_description'  => $data['profile_description'] ?? '',
            ':profile_picture_url'  => $data['profile_picture_url'] ?? '',
            ':social_links'         => json_encode($data['social_links'] ?? []),
            ':role'                 => $data['role'] ?? 'user',
            ':status'               => $data['status'] ?? 'active'
        ]);
    }

    // Get user by ID.
    public function getById(int $id): ?array {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Update user by ID.
    public function update(int $id, array $data): bool {
        $query = "UPDATE {$this->table} 
                  SET username = :username, email = :email, password_hash = :password_hash, 
                      profile_description = :profile_description, profile_picture_url = :profile_picture_url, 
                      social_links = :social_links, role = :role, status = :status
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':id'                   => $id,
            ':username'             => $data['username'],
            ':email'                => $data['email'],
            ':password_hash'        => $data['password_hash'],
            ':profile_description'  => $data['profile_description'] ?? '',
            ':profile_picture_url'  => $data['profile_picture_url'] ?? '',
            ':social_links'         => json_encode($data['social_links'] ?? []),
            ':role'                 => $data['role'] ?? 'user',
            ':status'               => $data['status'] ?? 'active'
        ]);
    }

    // Delete a user by ID.
    public function delete(int $id): bool {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Find user by email.
    public function findByEmail(string $email): ?array {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Find user by username.
    public function findByUsername(string $username): ?array {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':username' => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
?>
