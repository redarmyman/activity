<?php

namespace App\Tests\Unit\Service;

use App\Service\ActivityQueryService;
use PHPUnit\Framework\TestCase;

class ActivityQueryServiceTest extends TestCase
{
    public function testRainingRequest(): void
    {
        $queryService = new ActivityQueryService();

        $query = $queryService->getQuery(true);

        $this->assertArrayHasKey('type', $query);
        $this->assertNotEquals('social', $query['type']);
        $this->assertTrue(in_array($query['type'], [
            'education',
            'recreational',
            'diy',
            'cooking',
            'relaxation',
            'music',
            'busywork',
        ], true));
        $this->assertSame(1, $query['participants']);
    }

    public function testNotRainingRequest(): void
    {   
        $queryService = new ActivityQueryService();
        
        $query = $queryService->getQuery(false);
        
        $this->assertArrayHasKey('type', $query);
        $this->assertSame('social', $query['type']);
        $this->assertTrue(in_array($query['participants'], [2,3,4]));
    }
}

