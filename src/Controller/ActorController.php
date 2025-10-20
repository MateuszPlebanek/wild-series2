<?php
// src/Controller/ActorController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ActorRepository $repo): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $repo->findAll(),
        ]);
    }

    // ParamConverter : {id} -> Actor $actor
    #[Route('/{id<\d+>}', name: 'show')]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }
}
