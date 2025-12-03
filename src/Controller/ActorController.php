<?php
// src/Controller/ActorController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($actor);
            $em->flush();

            $this->addFlash('success', 'Actor created successfully.');

            return $this->redirectToRoute('actor_show', [
                'id' => $actor->getId(),
            ]);
        }

        return $this->render('actor/new.html.twig', [
            'form'  => $form,
            'actor' => $actor,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'show')]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Actor $actor, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Actor updated successfully.');

            return $this->redirectToRoute('actor_show', [
                'id' => $actor->getId(),
            ]);
        }

        return $this->render('actor/edit.html.twig', [
            'form'  => $form,
            'actor' => $actor,
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Actor $actor, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_actor_'.$actor->getId(), $request->request->get('_token'))) {
            $em->remove($actor);
            $em->flush();
            $this->addFlash('danger', 'Actor deleted.');
        }

        return $this->redirectToRoute('actor_index');
    }
}
