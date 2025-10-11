<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            'title' => 'Walking Dead',
            'synopsis' => 'Des zombies envahissent la terre.',
            'poster' => 'https://example.com/walkingdead.jpg',
            'category' => 'category_Action',
        ],
        [
            'title' => 'The Witcher',
            'synopsis' => 'Un sorceleur combat des monstres.',
            'poster' => 'https://example.com/witcher.jpg',
            'category' => 'category_Fantastique',
        ],
        [
            'title' => 'One Piece',
            'synopsis' => 'Luffy veut devenir le roi des pirates.',
            'poster' => 'https://example.com/onepiece.jpg',
            'category' => 'category_Aventure',
        ],
        [
            'title' => 'Your Name',
            'synopsis' => 'Deux adolescents échangent leurs corps.',
            'poster' => 'https://example.com/yourname.jpg',
            'category' => 'category_Animation',
        ],
        [
            'title' => 'Stranger Things',
            'synopsis' => 'Une ville hantée par des forces surnaturelles.',
            'poster' => 'https://example.com/strangerthings.jpg',
            'category' => 'category_Horreur',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programData) {
            $program = new Program();
            $program->setTitle($programData["title"]);
            $program->setSynopsis($programData["synopsis"]);
            $program->setPoster($programData["poster"]);
            $program->setCategory($this->getReference($programData["category"], Category::class));
            $manager->persist($program);
        }
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
