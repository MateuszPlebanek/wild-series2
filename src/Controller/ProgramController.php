<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/program', name: 'program_')]
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

    // 1) show(): ParamConverter sur {id} -> Program (cas simple)
    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    // 2) showSeason(): ParamConverter avec MapEntity car placeholders != noms d'entités
    // URL officielle du sujet : /program/{programId}/seasons/{seasonId}
    #[Route('/{programId}/seasons/{seasonId}', name: 'season_show')]
    public function showSeason(
        #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
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

    // 3) showEpisode(): ParamConverter x3 avec MapEntity (placeholders ...Id)
    // URL officielle du sujet :
    // /program/{programId}/season/{seasonId}/episode/{episodeId}
    #[Route('/{programId}/season/{seasonId}/episode/{episodeId}', name: 'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
        #[MapEntity(mapping: ['seasonId'  => 'id'])] Season $season,
        #[MapEntity(mapping: ['episodeId' => 'id'])] Episode $episode
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
