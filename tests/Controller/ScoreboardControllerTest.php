<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ScoreboardControllerTest extends WebTestCase
{
    public function testAddWithSubmitForm(): void
    {
        $client = static::createClient();
        $client->request('POST', '/add-event');

        $client->submitForm('game[save]', [
           'game[homeTeam]' => 'Poland',
           'game[awayTeam]' => 'Mexico',
        ]);

        $this->assertResponseRedirects();
    }

    public function testAddSubmitErrorForm(): void
    {
        $client = static::createClient();
        $client->request('POST', '/add-event');

        $client->submitForm('game[save]', [
            'game[homeTeam]' => '',
            'game[awayTeam]' => '',
        ]);

        $this->assertResponseIsUnprocessable();
    }

    public function testAddWithoutSubmitForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/add-event');

        $this->assertResponseIsSuccessful();
    }

    public function testView(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Scoreboard!');
    }

    public function testSummary(): void
    {
        $client = static::createClient();
        $client->request('GET', '/summary');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div');
        $this->assertSelectorExists('ol');
        $this->assertSelectorExists('li');
    }

    public function testUpdateNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/update/'.random_int(10000000000, PHP_INT_MAX));

        $this->assertResponseStatusCodeSame($client->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
    }

    public function testFinishNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/finish/'.random_int(10000000000, PHP_INT_MAX));

        $this->assertResponseStatusCodeSame($client->getResponse()->getStatusCode(), Response::HTTP_NOT_FOUND);
    }
}
