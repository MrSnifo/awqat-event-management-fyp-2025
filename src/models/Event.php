<?php
require_once '../config/Database.php';

class Event {
    private $conn;
    private $table = 'events';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new event
    public function create(array $data): int | false
    {
        $query = "INSERT INTO events 
                  (user_id, title, start_date, end_date, start_time, end_time, 
                   description, tags, cover_image_url, status) 
                  VALUES 
                  (:user_id, :title, :start_date, :end_date, :start_time, :end_time, 
                   :description, :tags, :cover_image_url, :status)";
        
        $stmt = $this->conn->prepare($query);
    
        $tagsJson = json_encode($data['tags'] ?? []);
    
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':tags', $tagsJson);
        $stmt->bindParam(':cover_image_url', $data['cover_image_url']);
        $stmt->bindParam(':status', $data['status']);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
    
        return false;
    }
    

    // Get event by ID
    public function getById(int $id) {
        $query = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all events for a specific user
    public function getByUserId(int $userId) {
        $query = "SELECT * FROM events WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update an existing event
    public function update(int $id, array $data) {
        $query = "UPDATE events SET 
                 title = :title,
                 start_date = :start_date,
                 end_date = :end_date,
                 start_time = :start_time,
                 end_time = :end_time,
                 description = :description,
                 tags = :tags,
                 cover_image_url = :cover_image_url,
                 status = :status
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $tagsJson = json_encode($data['tags'] ?? []);
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':tags', $tagsJson);
        $stmt->bindParam(':cover_image_url', $data['cover_image_url']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Delete an event
    public function delete(int $id) {
        $query = "DELETE FROM events WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Get all active events
    public function getAllVerified() {
        $query = "SELECT * FROM events WHERE status = 1 ORDER BY start_date, start_time";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get upcoming events
    public function getUpcomingEvents(int $days = 30) {
        $query = "SELECT * FROM events 
                 WHERE status = 1 
                 AND start_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY) 
                 ORDER BY start_date, start_time";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $days);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>