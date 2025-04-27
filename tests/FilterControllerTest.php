<?php
use PHPUnit\Framework\TestCase;


require_once __DIR__ . '/../src/controllers/filters.php';

class FilterControllerTest extends TestCase
{
    private $filters;

    protected function setUp(): void
    {
        $this->filters = new FilterController();
    }

    public function testRecentSortReturnsEvents()
    {
        $result = $this->filters->filter('recent', [], null);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    public function testInterestsHighSortReturnsOrderedEvents()
    {
        $result = $this->filters->filter('interests_high', [], null);
        $this->assertIsArray($result);
        
        $prevInterest = PHP_INT_MAX;
        foreach ($result as $event) {
            $this->assertLessThanOrEqual($prevInterest, $event['interests'] ?? 0);
            $prevInterest = $event['interests'] ?? 0;
        }
    }

    public function testInterestsLowSortReturnsOrderedEvents()
    {
        $result = $this->filters->filter('interests_low', [], null);
        $this->assertIsArray($result);
        
        $prevInterest = 0;
        foreach ($result as $event) {
            $this->assertGreaterThanOrEqual($prevInterest, $event['interests'] ?? 0);
            $prevInterest = $event['interests'] ?? 0;
        }
    }

    public function testTagFilteringWorks()
    {
        $allEvents = $this->filters->filter('recent', [], null);
        $sampleTag = $allEvents[0]['tags'][0] ?? 'music';
        
        $result = $this->filters->filter('recent', [$sampleTag], null);
        $this->assertIsArray($result);
        
        foreach ($result as $event) {
            $this->assertContains($sampleTag, $event['tags'] ?? []);
        }
    }

    public function testFormatAddsRequiredFields()
    {
        $allEvents = $this->filters->filter('recent', [], null);
        $sampleEvent = $allEvents[0] ?? null;
        
        $this->assertArrayHasKey('status_text', $sampleEvent);
        $this->assertArrayHasKey('interests', $sampleEvent);
        $this->assertArrayHasKey('isInterested', $sampleEvent);
    }
}