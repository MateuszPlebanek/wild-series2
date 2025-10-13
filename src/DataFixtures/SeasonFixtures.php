<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($p = 1; $p <= 10; $p++) {
            $programRef = 'program_' . $p;

            for ($s = 1; $s <= 5; $s++) { // <<< 5 saisons fixes
                $season = new Season();
                $season->setNumber($s);
                $season->setYear((int) $faker->numberBetween(1990, (int) date('Y')));
                $season->setDescription($faker->optional()->paragraph());
                $season->setProgram($this->getReference($programRef, Program::class));

                $manager->persist($season);

                // Référence pour EpisodesFixtures
                $this->addReference("season_{$p}_{$s}", $season);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ProgramFixtures::class];
    }
}
