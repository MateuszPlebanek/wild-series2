<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($p = 1; $p <= 10; $p++) {
            for ($s = 1; $s <= 5; $s++) {
                $seasonRef = "season_{$p}_{$s}";

                /** @var Season $season */
                $season = $this->getReference($seasonRef, Season::class);

                for ($e = 1; $e <= 10; $e++) { 
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence(4));
                    $episode->setNumber($e);
                    $episode->setSynopsis($faker->paragraphs(2, true));
                    $episode->setSeason($season);

                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SeasonFixtures::class];
    }
}
