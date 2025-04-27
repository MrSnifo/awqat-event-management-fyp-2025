<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserInteraction.php';

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
            'interested' => 4,
            'uninterested' => -1,
        ];
    
        if (!isset($validInteractions[$type])) {
            return ['success' => false, 'message' => 'Invalid interaction type'];
        }
    
        // Handle view interactions (only once per day)
        if ($type === 'view') {
            $existingView = $this->interaction->get($userId, $eventId, 'view');
            
            if ($existingView) {
                $createdAt = new DateTime($existingView['created_at']);
                $now = new DateTime();
                $diff = $now->diff($createdAt);
                
                if ($diff->days < 1) {
                    return ['success' => false, 'message' => 'View already recorded today'];
                }
            }
        }
        // Handle interested/uninterested (toggle behavior)
        elseif ($type === 'interested' || $type === 'uninterested') {
            $existing = $this->interaction->get($userId, $eventId, $type);
            $oppositeType = ($type === 'interested') ? 'uninterested' : 'interested';
            
            // If same interaction exists, update to opposite type
            if ($existing) {
                $success = $this->interaction->update(
                    $existing['id'],
                    $oppositeType,
                    $validInteractions[$oppositeType]
                );
                
                return [
                    'success' => $success,
                    'message' => $success ? 'Updated to '.$oppositeType : 'Failed to update'
                ];
            }
            
            // Remove opposite type if exists
            $existingOpposite = $this->interaction->get($userId, $eventId, $oppositeType);
            if ($existingOpposite) {
                $this->interaction->remove($existingOpposite['id']);
            }
        }
    
        // Record the new interaction
        $success = $this->interaction->record(
            $userId,
            $eventId,
            $type,
            $validInteractions[$type]
        );
    
        return [
            'success' => $success,
            'message' => $success ? ucfirst($type).' recorded' : 'Failed to record'
        ];
    }

}