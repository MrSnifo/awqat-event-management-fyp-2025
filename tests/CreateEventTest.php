<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/event.php';

class CreateEventTest extends TestCase {
    private $eventController;

    protected function setUp(): void {
        $this->eventController = new EventController();
    }

    public function testCreateEventWithValidData() {
            $randomTitle = 'Test Event ' . uniqid();
            $randomLocation = 'Location ' . rand(1, 1000);

            $eventData = [
                'user_id' => 1,
                'title' => $randomTitle,
                'location' => $randomLocation,
                'start_date' => '2025-05-01',
                'end_date' => '2025-05-02',
                'start_time' => '10:00',
                'end_time' => '12:00',
                'description' => 'Test Description',
                'tags' => ['test', 'php']
            ];

            $response = $this->eventController->createEvent($eventData);

            $this->assertIsArray($response);
            $this->assertArrayHasKey('success', $response);
            $this->assertTrue($response['success']);
        }


    public function testCreateEventMissingTitle()
    {
        $eventData = [
            'user_id' => 1,
            // 'title' => missing
            'location' => 'Test Location',
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-02',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'description' => 'Test Description',
            'tags' => ['test', 'php']
        ];

        $response = $this->eventController->createEvent($eventData);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
    }
}
