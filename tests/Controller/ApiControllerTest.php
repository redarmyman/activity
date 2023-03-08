<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testIsItRainingSuccessfulRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lat=51.110743&lon=17.035002');

        $this->assertResponseIsSuccessful();
    }
}

