<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $season = new Season();
        $season->setNumber(1);
        $season->setYear(2021);
        $season->setDescription('Première saison d’Arcane.');
        $season->setProgram($this->getReference('program_Arcane', Program::class)); 

        $manager->persist($season);
        $manager->flush();

        // Référence pour les épisodes
        $this->addReference('season1_Arcane', $season);
    }

    public function getDependencies(): array
    {
        return [ProgramFixtures::class];
    }
}
