<?php

namespace App\Form;

use App\Entity\Episode;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('synopsis')
            ->add('number')
            ->add('duration', IntegerType::class, [    
                'label' => 'Duration (minutes)',
            ])
            ->add('season', EntityType::class, [
                'class'        => Season::class,
                'choice_label' => function (Season $s) {
                    $p = $s->getProgram();
                    return sprintf('%s — S%s (%s)',
                        $p ? $p->getTitle() : '—',
                        $s->getNumber(),
                        $s->getYear()
                    );
                },
                'placeholder'  => 'Choose a season',
                'label'        => 'Season',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
