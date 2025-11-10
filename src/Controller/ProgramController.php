<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;

#[Route('/programs', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'website'  => 'Wild Series',
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug((string) $program->getTitle())->lower();
            $program->setSlug($slug);

            $em->persist($program);
            $em->flush();

            $this->addFlash('success', 'Program created!');
            return $this->redirectToRoute('program_show', ['slug' => $program->getSlug()]);
        }

        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'show', requirements: ['slug' => '[a-z0-9-]+'])]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Program $program, ProgramDuration $programDuration
    ): Response {
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program),
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        #[MapEntity(mapping: ['slug' => 'slug'])] Program $program,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program->setSlug($slugger->slug((string) $program->getTitle())->lower());

            $em->flush();

            $this->addFlash('success', 'Program updated!');
            return $this->redirectToRoute('program_show', ['slug' => $program->getSlug()]);
        }

        return $this->render('program/edit.html.twig', [
            'form' => $form,
            'program' => $program,
        ]);
    }

    #[Route('/{programSlug}/seasons/{seasonId}', name: 'season_show')]
    public function showSeason(
        #[MapEntity(mapping: ['programSlug' => 'slug'])] Program $program,
        #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season
    ): Response {
        if ($season->getProgram() !== $program) {
            throw $this->createNotFoundException('Cette saison n’appartient pas à ce programme.');
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season'  => $season,
        ]);
    }

    #[Route('/{programSlug}/seasons/{seasonId}/episodes/{episodeSlug}', name: 'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['programSlug' => 'slug'])] Program $program,
        #[MapEntity(mapping: ['seasonId'  => 'id'])] Season $season,
        #[MapEntity(mapping: ['episodeSlug' => 'slug'])] Episode $episode
    ): Response {
        if ($season->getProgram() !== $program || $episode->getSeason() !== $season) {
            throw $this->createNotFoundException('Incohérence programme/saison/épisode.');
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season'  => $season,
            'episode' => $episode,
        ]);
    }
}
