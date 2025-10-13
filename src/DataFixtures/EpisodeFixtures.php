<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $episode1 = new Episode();
        $episode1->setTitle('Welcome to the Playground');
        $episode1->setNumber(1);
        $episode1->setSynopsis('Vi et Powder découvrent le monde souterrain de Piltover.');
        $episode1->setSeason($this->getReference('season1_Arcane', Season::class));

        $episode2 = new Episode();
        $episode2->setTitle('Some Mysteries Are Better Left Unsolved');
        $episode2->setNumber(2);
        $episode2->setSynopsis('Les tensions montent après le vol d’un artefact.');
        $episode2->setSeason($this->getReference('season1_Arcane', Season::class));

        $manager->persist($episode1);
        $manager->persist($episode2);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SeasonFixtures::class];
    }
}
