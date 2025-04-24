<?php
class UserInteraction {
    private $conn;
    private $table = 'user_interactions';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Records interaction without metadata
    public function record(string $userId, string $eventId, string $type, int $weight): bool {
        $query = "INSERT INTO {$this->table} 
                 (user_id, event_id, interaction_type, weight) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $eventId, $type, $weight]);
    }


    // Gets basic interaction history
    public function getByUserId(string $userId): array {
        $query = "SELECT event_id, interaction_type, weight FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}