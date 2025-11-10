<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\String\Slugger\SluggerInterface;  

#[Route('/episode')]
final class EpisodeController extends AbstractController
{
    #[Route(name: 'app_episode_index', methods: ['GET'])]
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_episode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $episode->setSlug($slugger->slug((string) $episode->getTitle())->lower());
            
            $entityManager->persist($episode);
            $entityManager->flush();

            $this->addFlash('success', 'Episode created successfully.');
            return $this->redirectToRoute('app_episode_show', ['slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_episode_show', methods: ['GET'], requirements: ['slug' => '[a-z0-9-]+'])]
     public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_episode_edit', methods: ['GET', 'POST'], requirements: ['slug' => '[a-z0-9-]+'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Episode $episode, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episode->setSlug($slugger->slug((string) $episode->getTitle())->lower());
            $entityManager->flush();

            $this->addFlash('success', 'Episode updated successfully.');
            return $this->redirectToRoute('app_episode_show', ['slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_episode_delete', methods: ['POST'], requirements: ['slug' => '[a-z0-9-]+'])]
    public function delete(#[MapEntity(mapping: ['slug' => 'slug'])] Episode $episode, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'Episode deleted successfully.');
        return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
    }
}
