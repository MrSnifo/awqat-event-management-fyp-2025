<?php
require_once '../config/Database.php';
require_once '../models/Interest.php';
require_once '../models/Event.php';

class FilterController {
    private $event;
    private $interest;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        // Controllers
        $this->event = new Event($db);
        $this->interest = new InterestController($db);
    }


    private function run_ai(int $user_id): array {
        $python_binary = 'C:\Users\Snifo\AppData\Local\Programs\Python\Python313\python.exe';
        $python_script = 'C:\wamp64\www\PFA-2024-2025\src\scripts\recommender.py';
        
        $command = escapeshellcmd($python_binary . " " . $python_script . " " . escapeshellarg($user_id));
        $output = shell_exec($command);
        
        if ($output !== null) {
            $result = json_decode($output, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return ['success' => true, 'data' => $result];
            }
        }

        return ['success' => false, 'message' => 'Ai failed :('];
    }


    private function sortByInterest(array $events, string $sort): array {    
        usort($events, function($a, $b) use ($sort) {
            // Ensure values are integers
            $aInterests = (int)($a['interests'] ?? 0); // Check for typos ('interests' vs 'interests')
            $bInterests = (int)($b['interests'] ?? 0);
            
            return ($sort === 'desc') 
                ? $bInterests - $aInterests // High to Low
                : $aInterests - $bInterests; // Low to High
        });
        
        return $events;
    }


    private function sortByRecommended(array $events, array $recommended_event_ids): array {
        // Get just the ordered IDs from recommendations
        $orderedIds = $recommended_event_ids['data'] ?? [];
        
        // Create a map of event ID => event for quick lookup
        $eventsMap = [];
        foreach ($events as $event) {
            $eventsMap[$event['id']] = $event;
        }
        
        // Build the result array in recommended order
        $sortedEvents = [];
        foreach ($orderedIds as $id) {
            if (isset($eventsMap[$id])) {
                $sortedEvents[] = $eventsMap[$id];
            }
        }
        
        // Add any remaining events not in recommendations
        foreach ($eventsMap as $id => $event) {
            if (!in_array($id, $orderedIds)) {
                $sortedEvents[] = $event;
            }
        }
        
        return $sortedEvents;
    }

    private function format(array $events, ?int $userId = null): array {
        $today = new DateTime();
    
        foreach ($events as &$event) {
            // 1. Add event status (Happening Now / Upcoming / Past)
            $startDate = new DateTime($event['start_date']);
            $endDate = new DateTime($event['end_date'] ?? $event['start_date']);
    
            $isPast = $endDate < $today;
            $isUpcoming = $startDate > $today;
            $isActiveNow = (!$isPast && !$isUpcoming);
    
            if ($isActiveNow) {
                $event['status_text'] = 'Happening Now';
            } elseif ($isUpcoming) {
                $event['status_text'] = 'Upcoming';
            } else {
                $event['status_text'] = 'Past';
            }
    
            // 2. Add interest count
            $event["interests"] = $this->interest->getEventInterestCount($event["id"]);
    
            // 3. Check if user is interested.
            $event["isInterested"] = false;
            if ($userId) {
                $event["isInterested"] = $this->interest->hasUserInterest($userId, $event["id"]);
            }
        }
        unset($event);
        return $events;
    }


    public function filter(string $sort, array $tags, ?int $user_id) {
        $events = $this->format($this->event->getUpcomingEvents(), $user_id);
    
        switch ($sort) {
            case 'recommended':
                if (isset($user_id)){
                    // A ordred array of event ids.
                    $recommended_event_ids = $this->run_ai($user_id);
                    if($recommended_event_ids['success']){
                        $events = $this->sortByRecommended($events, $recommended_event_ids);
                        break;
                    }
                }
            
            case 'recent':
                // Events are already ordered by recent.
                break;
    
            case 'interests_high':
                $events = $this->sortByInterest($events, 'desc');
                break;
    
            case 'interests_low':
                $events = $this->sortByInterest($events, 'asc');
                break;
    
            default:
                break;
        }

        if (!empty($tags)) {
            $events = array_filter($events, function($event) use ($tags) {
                return !empty(array_intersect($tags, $event['tags'] ?? []));
            });
        }

        return $events;
    }


    private function run_search(string $query): array {
        $python_binary = 'C:\Users\Snifo\AppData\Local\Programs\Python\Python313\python.exe';
        $python_script = 'C:\wamp64\www\PFA-2024-2025\src\scripts\search.py';
        
        $command = escapeshellcmd($python_binary . " " . $python_script . " " . escapeshellarg($query));
        $output = shell_exec($command);
        
        if ($output !== null) {
            $result = json_decode($output, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return ['success' => true, 'data' => $result];
            }
        }

        return ['success' => false, 'message' => 'Ai failed :('];
    }


    public function search(string $query, ?int $user_id) {
        $result = $this->run_search($query);
        $events = [];
    
        if ($result['success']) {
            foreach ($result['data'] as $event_id) {
                $event = $this->event->getById($event_id);
                if ($event) {
                    $events[] = $event;
                }
            }
        }

        return $this->format($events, $user_id);;
    }



}
?>