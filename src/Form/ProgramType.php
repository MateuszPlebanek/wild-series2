<?php

namespace App\Form;

use App\Entity\Program;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis',
            ])
            ->add('poster', TextType::class, [
                'label' => 'Poster URL',
                'required' => false,
                'attr' => ['placeholder' =>'https://..']
            ])
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',   
                'placeholder'  => 'Choose a category',
                'label'        => 'Category',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
