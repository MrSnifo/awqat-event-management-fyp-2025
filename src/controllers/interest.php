<?php
require_once '../config/Database.php';
require_once '../models/Interest.php';
require_once '../models/Event.php';
require_once '../controllers/interaction.php';

class InterestController {
    private $eventInterest;
    private $interactionController;
    private $event;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->eventInterest = new EventInterest($db);
        $this->event = new Event($db);
        $this->interactionController = new InteractionController();
    }

    public function handleToggleInterest(): void {
        header('Content-Type: application/json');
        
        // Verify POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (empty($input['event_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Event ID is required']);
            return;
        }

        // Get user ID from session
        session_start();
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $eventId = (int)$input['event_id'];

        // Toggle interest and get result
        $result = $this->toggleInterest($userId, $eventId);
        
        if ($result['success']) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        
        echo json_encode($result);
    }


    private function toggleInterest(int $userId, int $eventId): array {
        // Validate event exists and is verified
        $event = $this->event->getById($eventId);
        if (!$event) {
            return ['success' => false, 'message' => 'Event not found'];
        }

        $hasInterest = $this->eventInterest->hasInterest($userId, $eventId);

        if ($hasInterest) {
            // Remove interest
            $this->interactionController->recordInteraction($userId, $eventId, 'uninterested');
            $success = $this->eventInterest->removeInterest($userId, $eventId);
            $message = $success ? 'Interest removed successfully' : 'Failed to remove interest';
            $action = 'removed';
        } else {
            // Add interest
            $this->interactionController->recordInteraction($userId, $eventId, 'interested');
            $success = $this->eventInterest->addInterest($userId, $eventId);
            $message = $success ? 'Interest added successfully' : 'Failed to add interest';
            $action = 'added';
        }

        if ($success) {
            // Get updated interest count
            $interestCount = $this->getEventInterestCount($eventId);
            
            return [
                'success' => true,
                'message' => $message,
                'action' => $action,
                'interestCount' => $interestCount,
                'hasInterest' => !$hasInterest // Return the new state
            ];
        }

        return ['success' => false, 'message' => $message];
    }

    public function getEventInterestCount(int $eventId): int {
        return $this->eventInterest->getEventInterestCount($eventId);
    }

    public function hasUserInterest(int $userId, int $eventId): bool {
        return $this->eventInterest->hasInterest($userId, $eventId);
    }


    public function getUserInterests(int $userid){
        return $this->eventInterest->getUserInterests($userid);
    }
}