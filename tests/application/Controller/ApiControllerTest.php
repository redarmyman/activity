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

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('code', $response);
        $this->assertSame($response['code'], 400);
        $this->assertArrayHasKey('message', $response);
        $this->assertSame($response['message'], 'Missing lat or lon parameter');
    }

    public function testIsItRainingMissingLonRequest(): void
    {   
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lat==51.110743');

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('code', $response);
        $this->assertSame($response['code'], 400);
        $this->assertArrayHasKey('message', $response);
        $this->assertSame($response['message'], 'Missing lat or lon parameter');
    }

    public function testIsItRainingWrongLongitudeRequest(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/v1/isitraining?lat=51.110743&lon=5000');
        
        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);
    
        $this->assertArrayHasKey('code', $response);
        $this->assertSame($response['code'], 400);
        $this->assertArrayHasKey('message', $response);
        $this->assertSame($response['message'], 'wrong longitude');
    }

    public function testIsItRainingWrongLatitudeRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lat=5000&lon=17.035002');

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('code', $response);
        $this->assertSame($response['code'], 400);
        $this->assertArrayHasKey('message', $response);
        $this->assertSame($response['message'], 'wrong latitude');
    }

    public function testIsItRainingSuccessfulRequest(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/v1/isitraining?lat=51.110743&lon=17.035002');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('code', $response);
        $this->assertSame($response['code'], 200);
        $this->assertArrayHasKey('isItRaining', $response);
        $this->assertIsBool($response['isItRaining']);
    }
}

