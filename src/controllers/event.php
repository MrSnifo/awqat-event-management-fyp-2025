<?php
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/User.php';

class EventController {
    private $event;
    private $user;
    
    public function __construct() {
        $database = new Database();
        $this->event = new Event($database->getConnection());
        $this->user = new User($database->getConnection());
    }

    public function createEvent(mixed $data): array {
        $required = [
            'user_id' => 'User ID',
            'title' => 'Event title',
            'location' => 'Location',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'start_time' => 'Start time',
            'end_time' => 'End time',
            'description' => 'Description',
            'tags' => 'Tags'
        ];
    
        foreach ($required as $field => $name) {
            if (!array_key_exists($field, $data)) {
                return ['success' => false, 'message' => $name . ' is required'];
            }
            
            if ($field === 'tags') {
                if (!is_array($data['tags']) || count($data['tags']) < 1) {
                    return ['success' => false, 'message' => 'At least one tag is required'];
                }
            } elseif (empty($data[$field])) {
                return ['success' => false, 'message' => $name . ' cannot be empty'];
            }
        }
    
        // Date validation
        if (!DateTime::createFromFormat('Y-m-d', $data['start_date'])) {
            return ['success' => false, 'message' => 'Invalid start date format (YYYY-MM-DD required)'];
        }
    
        if (!DateTime::createFromFormat('Y-m-d', $data['end_date'])) {
            return ['success' => false, 'message' => 'Invalid end date format (YYYY-MM-DD required)'];
        }
    
        if (strtotime($data['end_date']) < strtotime($data['start_date'])) {
            return ['success' => false, 'message' => 'End date cannot be before start date'];
        }
    
        // Time validation
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['start_time'])) {
            return ['success' => false, 'message' => 'Invalid start time format (HH:MM required)'];
        }
    
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['end_time'])) {
            return ['success' => false, 'message' => 'Invalid end time format (HH:MM required)'];
        }
    
        // Time comparison validation
        if ($data['start_date'] == $data['end_date'] && $data['end_time'] <= $data['start_time']) {
            return ['success' => false, 'message' => 'End time must be after start time for same-day events'];
        }

        if ($this->event->eventExists($data['title'], $data['start_date'], $data['location'])) {
            return ['success' => false, 'message' => 'Event with the same title, start date, and location already exists'];
        }
    
        // Prepare event data
        $eventData = [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'location' => $data['location'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'description' => $data['description'],
            'tags' => $data['tags'],
            'cover_image_url' => isset($data['cover_image_url']) ? $data['cover_image_url'] : null,
            'status' => 'unverified'
        ];
    
        $eventId = $this->event->create($eventData);
        if ($eventId) {
            return [
                'success' => true,
                'message' => 'Event created successfully',
                'event_id' => $eventId
            ];
        }
        return ['success' => false, 'message' => 'Failed to create event'];
    }

    // gets event and its creator
    public function getEvent(string $id): array {
        $eventData = $this->event->getById($id);
        if($eventData){
            $userData = $this->user->getById($eventData['user_id']);
            return ['success' => true, 'data' => $eventData, 'creator'=> $userData];
        }
        return ['success' => false, 'message' => 'Event not found'];
    }


    public function getevents(): array {
    return $this->event->getUpcomingEvents();
    }
}