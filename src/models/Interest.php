<?php
class EventInterest {
    private $conn;
    private $table = 'event_interests';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add an interest
    public function addInterest(int $userId, int $eventId): bool {
        // Check if already exists
        if ($this->hasInterest($userId, $eventId)) {
            return true; // Already exists, treat as success
        }

        $query = "INSERT INTO {$this->table} (user_id, event_id) VALUES (:user_id, :event_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Check if user has shown interest in the event
    public function hasInterest(int $userId, int $eventId): bool {
        $query = "SELECT id FROM {$this->table} WHERE user_id = :user_id AND event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Get all interests by a specific user
    public function getUserInterests(int $userId): array {
        $query = "SELECT event_id FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // returns array of event_ids
    }

    // Get all users interested in a specific event
    public function getEventInterestCount(int $eventId): int {
        $query = "SELECT COUNT(user_id) FROM {$this->table} WHERE event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // Remove interest
    public function removeInterest(int $userId, int $eventId): bool {
        $query = "DELETE FROM {$this->table} WHERE user_id = :user_id AND event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
