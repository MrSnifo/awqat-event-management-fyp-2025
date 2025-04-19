<?php
class User
{
    private $db;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $profile_description;
    public $profile_picture_url;
    public $social_links;
    public $role;
    public $status;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create a new user
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (username, email, password_hash, profile_description, profile_picture_url, social_links, role, status)
                  VALUES (:username, :email, :password_hash, :profile_description, :profile_picture_url, :social_links, :role, :status)";

        $stmt = $this->db->prepare($query);

        // Bind values (TO prevent SQL injection)
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $this->password_hash);
        $stmt->bindParam(':profile_description', $this->profile_description);
        $stmt->bindParam(':profile_picture_url', $this->profile_picture_url);
        $stmt->bindParam(':social_links', json_encode($this->social_links));
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read a user by id
    public function read($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user details
    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET username = :username, email = :email, password_hash = :password_hash, 
                      profile_description = :profile_description, profile_picture_url = :profile_picture_url, 
                      social_links = :social_links, role = :role, status = :status
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);

        // Bind values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $this->password_hash);
        $stmt->bindParam(':profile_description', $this->profile_description);
        $stmt->bindParam(':profile_picture_url', $this->profile_picture_url);
        $stmt->bindParam(':social_links', json_encode($this->social_links));
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a user
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Check if username exists
    public function usernameExists()
    {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Check if email exists
    public function emailExists()
    {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }


    public function findByUsernameOrEmail($login) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE username = :login OR email = :login 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function verifyPassword($password) {
        return password_verify($password, $this->password_hash);
    }
}
?>