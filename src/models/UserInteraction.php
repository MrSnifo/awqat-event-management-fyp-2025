<?php
class UserInteraction {
    private $conn;
    private $table = 'user_interactions';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Records interaction without metadata
    public function get(string $userId, string $eventId, string $type): ?array {
        $query = "SELECT * FROM {$this->table} 
                  WHERE user_id = ? AND event_id = ? AND interaction_type = ?
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $eventId, $type]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    public function update(int $id, string $type, int $weight): bool {
        $query = "UPDATE {$this->table} 
                  SET interaction_type = ?, weight = ?, updated_at = NOW() 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$type, $weight, $id]);
    }
    
    public function remove(int $id): bool {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function record(string $userId, string $eventId, string $type, int $weight): bool {
        $query = "INSERT INTO {$this->table} 
                 (user_id, event_id, interaction_type, weight) 
                 VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $eventId, $type, $weight]);
    }
}