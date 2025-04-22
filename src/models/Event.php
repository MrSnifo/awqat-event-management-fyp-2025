<?php
require_once '../config/Database.php';

class Event {
    private $conn;
    private $table = 'events';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new event
    public function create(array $data): int|false {
        $query = "INSERT INTO {$this->table} 
                 (user_id, title, location, start_date, end_date, start_time, end_time, 
                  description, tags, cover_image_url, status) 
                 VALUES 
                 (:user_id, :title, :location, :start_date, :end_date, :start_time, :end_time, 
                  :description, :tags, :cover_image_url, :status)";
        
        $stmt = $this->conn->prepare($query);
    
        $tagsJson = json_encode($data['tags'] ?? []);
        $status = $data['status'] ?? 'unverified';
    
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':tags', $tagsJson);
        $stmt->bindParam(':cover_image_url', $data['cover_image_url']);
        $stmt->bindParam(':status', $status);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
    
        return false;
    }

    public function eventExists(string $title, string $start_date, string $location): bool {
        $query = "SELECT id FROM {$this->table} WHERE title = :title AND start_date = :start_date AND location = :location";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':location', $location);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Return true if any event exists
    }

    // Get event by ID
    public function getById(int $id): array|false {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($event) {
            $event['tags'] = json_decode($event['tags'], true) ?? [];
        }
        return $event;
    }

    // Get all events for a specific user
    public function getByUserId(int $userId): array {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY start_date, start_time";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($events as &$event) {
            $event['tags'] = json_decode($event['tags'], true) ?? [];
        }
        return $events;
    }

    // Update an existing event
    public function update(int $id, array $data): bool {
        $query = "UPDATE {$this->table} SET 
                 title = :title,
                 location = :location,
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
        $status = $data['status'] ?? 'unverified';
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':tags', $tagsJson);
        $stmt->bindParam(':cover_image_url', $data['cover_image_url']);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Delete an event
    public function delete(int $id): bool {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Get all verified events
    public function getAllVerified(): array {
        $query = "SELECT * FROM {$this->table} WHERE status = 'verified' ORDER BY start_date, start_time";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($events as &$event) {
            $event['tags'] = json_decode($event['tags'], true) ?? [];
        }
        return $events;
    }

    // Get upcoming events
    public function getUpcomingEvents(int $days = 30): array {
        $query = "SELECT * FROM {$this->table} 
                 WHERE status = 'verified' 
                 AND start_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY) 
                 ORDER BY start_date, start_time";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($events as &$event) {
            $event['tags'] = json_decode($event['tags'], true) ?? [];
        }
        return $events;
    }

    // Increment interest count
    public function incrementInterestCount(int $eventId): bool {
        $query = "UPDATE {$this->table} SET interests = interests + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Decrement interest count
    public function decrementInterestCount(int $eventId): bool {
        $query = "UPDATE {$this->table} SET interests = GREATEST(0, interests - 1) WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Change event status
    public function changeStatus(int $eventId, string $status): bool {
        $validStatuses = ['blocked', 'unverified', 'verified'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}