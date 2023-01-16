<?php

namespace App\Tests\Form;

use App\Entity\Game;
use App\Form\GameType;
use Symfony\Component\Form\Test\TypeTestCase;

class GameTypeTest extends TypeTestCase
{
    /**
     * @dataProvider getValidTestData
     */
    public function testSubmitValidData($formData): void
    {
        $model = new Game();
        $form = $this->factory->create(GameType::class, $model);

        $expected = new Game();
        $expected->setHomeTeam($formData['homeTeam']);
        $expected->setAwayTeam($formData['awayTeam']);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }

    public function getValidTestData(): array
    {
        return [
            [
                'data' => [
                    'homeTeam' => 'Poland',
                    'awayTeam' => 'Mexico',
                ],
            ],
        ];
    }

    public function testFormView(): void
    {
        $formData = new Game();
        $formData->setHomeTeam('Poland');
        $formData->setAwayTeam('Mexico');

        $view = $this->factory->create(GameType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('data', $view->vars);
        $this->assertArrayHasKey('save', $view->vars['form']->children);
        $this->assertArrayHasKey('homeTeam', $view->vars['form']->children);
        $this->assertArrayHasKey('awayTeam', $view->vars['form']->children);
        $this->assertSame('Poland', $view->vars['data']->getHomeTeam());
        $this->assertSame('Mexico', $view->vars['data']->getAwayTeam());
    }
}
