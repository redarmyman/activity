<?php

namespace App\Tests\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testIsItRainingMissingLatRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lon=51.110743');

        $this->assertResponseStatusCodeSame(400);
    }

    public function testIsItRainingMissingLonRequest(): void
    {   
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/v1/isitraining?lat==51.110743');
        
        $this->assertResponseStatusCodeSame(400);
    }

    public function testIsItRainingWrongLongitudeRequest(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/v1/isitraining?lat=51.110743&lon=5000');
        
        $this->assertResponseStatusCodeSame(400);
    }

    public function testIsItRainingWrongLatitudeRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lat=5000&lon=17.035002');

        $this->assertResponseStatusCodeSame(400);
    }

    public function testIsItRainingSuccessfulRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lat=51.110743&lon=17.035002');

        $this->assertResponseIsSuccessful();
    }
}

