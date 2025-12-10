<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const CONTRIBUTOR_USER = 'contributor_user';
    public const SECOND_CONTRIBUTOR_USER = 'second_contributor_user';
    public const ADMIN_USER = 'admin_user';
   
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Création d’un utilisateur de type “contributeur” (= auteur)
        $contributor = new User();
        $contributor->setEmail('contributor@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor,
            'contributorpassword'
        );

        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);

        $this->addReference(self::CONTRIBUTOR_USER, $contributor);

        $secondContributor = new User();
        $secondContributor->setEmail('other@monsite.com');
        $secondContributor->setRoles(['ROLE_CONTRIBUTOR']);

        $secondContributor->setPassword(
            $this->passwordHasher->hashPassword(
                $secondContributor,
                'testpassword'
            )
        );

        $manager->persist($secondContributor);
        $this->addReference(self::SECOND_CONTRIBUTOR_USER, $secondContributor);


        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'adminpassword'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);
        
        $this->addReference(self::ADMIN_USER, $admin);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}