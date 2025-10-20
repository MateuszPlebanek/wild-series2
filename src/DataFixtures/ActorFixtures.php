<?php
namespace App\DataFixtures;

use App\Entity\Actor;
use App\Repository\ProgramRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly ProgramRepository $programRepository) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $allPrograms = $this->programRepository->findAll();
        if (count($allPrograms) === 0) {
            throw new \RuntimeException('Aucun Program en base. Charge d’abord les Program fixtures.');
        }

        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());

            $nb = min(3, count($allPrograms));
            $picked = (array) $faker->randomElements($allPrograms, $nb);
            foreach ($picked as $program) {
                $actor->addProgram($program); 
            }

            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ProgramFixtures::class];
    }
}
