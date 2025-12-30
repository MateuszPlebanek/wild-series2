<?php

namespace App\Twig\Components;

use App\Entity\Program;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class WatchList
{
    use DefaultActionTrait;

    #[LiveProp]
    public Program $program;

    public function __construct(
        private Security $security,
        private EntityManagerInterface $em
    ) {}

    #[LiveAction]
    public function toggle(): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        if ($user->getWatchlist()->contains($this->program)) {
            $user->removeFromWatchlist($this->program);
        } else {
            $user->addToWatchlist($this->program);
        }

        $this->em->flush();
    }
}
