<?php 
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/event.php';

class EventControllerTest extends TestCase
{
    // Declare properties to store fake objects and the controller we are testing
    private $eventFakeObject;
    private $userFakeObject;
    private $eventController;

    // This method runs before each test to set up the test environment
    protected function setUp(): void
    {
        // Create a fake Database object
        $databaseFake = $this->createMock(Database::class);

        // Create a fake Event object, only focusing on two methods: 'create' and 'eventExists'
        $this->eventFakeObject = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([$databaseFake])  // Pass the fake Database to the Event constructor
            ->onlyMethods(['create', 'eventExists'])  // Focus on these two methods
            ->getMock();

        // Create a fake User object
        $this->userFakeObject = $this->getMockBuilder(User::class)
            ->setConstructorArgs([$databaseFake])
            ->getMock();

        // Create an EventController using the fake Event and fake User objects
        $this->eventController = new EventController($this->eventFakeObject, $this->userFakeObject);
    }

    // Test for successfully creating an event
    public function testCreateEventSuccess()
    {
        // Sample data to create an event
        $data = [
            'user_id' => 1,
            'title' => 'Test Event',
            'location' => 'Test Location',
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-02',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'description' => 'Test Description',
            'tags' => ['conference', 'technology']
        ];

        // Make the fake Event object say there's no existing event with the same title and date
        $this->eventFakeObject->method('eventExists')->willReturn(false);
        
        // Make the fake Event object pretend to create an event and return a fake event ID (123)
        $this->eventFakeObject->method('create')->willReturn(123);

        // Call the controller to create the event using the data
        $result = $this->eventController->createEvent($data);

        // Assert that the result shows success
        $this->assertTrue($result['success']);
        $this->assertEquals('Event created successfully', $result['message']);
        $this->assertEquals(123, $result['event_id']);
    }

    // Test for when a required field is missing (user_id)
    public function testCreateEventMissingField()
    {
        // Sample data with missing 'user_id'
        $data = [
            'title' => 'Test Event',
            'location' => 'Test Location',
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-02',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'description' => 'Test Description',
            'tags' => ['conference', 'technology']
        ];

        // Call the controller to try to create the event
        $result = $this->eventController->createEvent($data);

        // Assert that it failed because the 'user_id' is missing
        $this->assertFalse($result['success']);
        $this->assertEquals('User ID is required', $result['message']);
    }

    // Test for when the start date format is invalid
    public function testCreateEventInvalidDateFormat()
    {
        // Sample data with an invalid date format for 'start_date'
        $data = [
            'user_id' => 1,
            'title' => 'Test Event',
            'location' => 'Test Location',
            'start_date' => '01-05-2025', // Invalid format
            'end_date' => '2025-05-02',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'description' => 'Test Description',
            'tags' => ['conference', 'technology']
        ];

        // Call the controller to try to create the event
        $result = $this->eventController->createEvent($data);

        // Assert that it failed because the start date format is invalid
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid start date format (YYYY-MM-DD required)', $result['message']);
    }

    // Test for when an event with the same title, date, and location already exists
    public function testCreateEventDuplicateEvent()
    {
        // Sample data for a duplicate event
        $data = [
            'user_id' => 1,
            'title' => 'Duplicate Event',
            'location' => 'Test Location',
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-02',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'description' => 'Test Description',
            'tags' => ['conference', 'technology']
        ];

        // Make the fake Event object say that the event already exists
        $this->eventFakeObject->method('eventExists')->willReturn(true);

        // Call the controller to try to create the event
        $result = $this->eventController->createEvent($data);

        // Assert that it failed because the event is a duplicate
        $this->assertFalse($result['success']);
        $this->assertEquals('Event with the same title, start date, and location already exists', $result['message']);
    }
}
