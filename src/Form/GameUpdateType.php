<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('homeTeamScore', IntegerType::class, [
                'attr' => [
                    'min' => '0',
                ],
            ])
            ->add('awayTeamScore', IntegerType::class, [
                'attr' => [
                    'min' => '0',
                ],
            ])
            ->add('update', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mb-2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
