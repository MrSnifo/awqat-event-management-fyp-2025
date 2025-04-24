<?php
require_once '../config/Database.php';
require_once '../models/UserInteraction.php';

class InteractionController {
    private $interaction;
    
    public function __construct() {
        $database = new Database();
        $this->interaction = new UserInteraction($database->getConnection());
    }

    // Records basic user interaction with an event
    public function recordInteraction(string $userId, string $eventId, string $type): array {
        $validInteractions = [
            'view' => 1,
            'share' => 2,
            'interested' => 5,
            'uninterested' => -1,
        ];

        if (!isset($validInteractions[$type])) {
            return ['success' => false, 'message' => 'Invalid interaction type'];
        }

        $success = $this->interaction->record(
            $userId,
            $eventId,
            $type,
            $validInteractions[$type]
        );

        return [
            'success' => $success,
            'message' => $success ? 'Interaction recorded' : 'Failed to record'
        ];
    }

    // Gets a user's interaction history (simple array of event IDs)
    public function getUserInteractions(string $userId): array {
        return $this->interaction->getByUserId($userId);
    }
}