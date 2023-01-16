<?php

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScoreboardControllerTest extends WebTestCase
{
    public function testView(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Scoreboard!');
    }

    public function testAddWithSubmitForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/add-event');

        $client->submitForm('game[save]', [
           'game[homeTeam]' => 'Poland',
           'game[awayTeam]' => 'Mexico',
        ]);

        $this->assertResponseRedirects();
    }

    public function testAddWithoutSubmitForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/add-event');

        $this->assertResponseIsSuccessful();
    }
}
