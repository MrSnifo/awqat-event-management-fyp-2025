<?php
require_once '../config/Database.php';

class EventInterest {
    private $conn;
    private $table = 'event_interests';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add interest to an event
    public function addInterest(int $userId, int $eventId): bool {
        $query = "INSERT INTO event_interests (user_id, event_id) VALUES (:user_id, :event_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    // Remove interest from an event
    public function removeInterest(int $userId, int $eventId): bool {
        $query = "DELETE FROM event_interests WHERE user_id = :user_id AND event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Check if user is interested in an event
    public function isInterested(int $userId, int $eventId): bool {
        $query = "SELECT COUNT(*) FROM event_interests WHERE user_id = :user_id AND event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Get all events a user is interested in
    public function getUserInterests(int $userId): array {
        $query = "SELECT e.* FROM events e
                 JOIN event_interests ei ON e.id = ei.event_id
                 WHERE ei.user_id = :user_id
                 ORDER BY e.start_date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all users interested in an event
    public function getEventInterests(int $eventId): array {
        $query = "SELECT u.id, u.username, u.email FROM users u
                 JOIN event_interests ei ON u.id = ei.user_id
                 WHERE ei.event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>