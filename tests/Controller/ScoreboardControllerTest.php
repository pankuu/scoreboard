<?php

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScoreboardControllerTest extends WebTestCase
{
    public function testView(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        dump($client->getResponse());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Scoreboard!');
    }
}