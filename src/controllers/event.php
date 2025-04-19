<?php
require_once '../config/Database.php';
require_once '../models/Event.php';

class EventController {
    private $event;
    
    public function __construct() {
        $database = new Database();
        $this->event = new Event($database->getConnection());
    }

    public function createEvent(mixed $data) {
        $required = [
            'user_id' => 'User ID',
            'title' => 'Event title',
            'start_date' => 'Start date',
            'start_time' => 'Start time',
            'description' => 'Description',
            'tags' => 'Tags',
            'cover_image_url' => 'Cover image URL',
            'status' => 'Status'
        ];

        foreach ($required as $field => $name) {
            if (!array_key_exists($field, $data)) {
                return ['success' => false, 'message' => $name . ' is required'];
            }
            
            // Special handling for different field types
            if ($field === 'tags') {
                if (!is_array($data['tags']) || count($data['tags']) < 1) {
                    return ['success' => false, 'message' => 'At least one tag is required'];
                }
            } elseif (empty($data[$field]) && $data[$field] !== 0) { // Allow 0 as valid status
                return ['success' => false, 'message' => $name . ' cannot be empty'];
            }
        }

        // Date validation
        if (!DateTime::createFromFormat('Y-m-d', $data['start_date'])) {
            return ['success' => false, 'message' => 'Invalid start date format (YYYY-MM-DD required)'];
        }

        if (!empty($data['end_date'])) {
            if (!DateTime::createFromFormat('Y-m-d', $data['end_date'])) {
                return ['success' => false, 'message' => 'Invalid end date format (YYYY-MM-DD required)'];
            }
            if (strtotime($data['end_date']) < strtotime($data['start_date'])) {
                return ['success' => false, 'message' => 'End date cannot be before start date'];
            }
        }

        // Time validation
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['start_time'])) {
            return ['success' => false, 'message' => 'Invalid start time format (HH:MM required)'];
        }

        if (!empty($data['end_time'])) {
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['end_time'])) {
                return ['success' => false, 'message' => 'Invalid end time format (HH:MM required)'];
            }
        }

        // Status validation (Aktheria mn server side.)
        if (!in_array($data['status'], [0, 1])) {
            return ['success' => false, 'message' => 'Invalid status value (must be 0 or 1)'];
        }

        // Prepare event data
        $eventData = [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'] ?? null,
            'description' => $data['description'], // Now required
            'tags' => $data['tags'],
            'cover_image_url' => $data['cover_image_url'], // Now required
            'status' => $data['status']
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
}