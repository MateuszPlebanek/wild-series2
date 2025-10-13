<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
        ]);
    }
#[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$id.' found in program\'s table.');
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }
    #[Route('/{programId<\d+>}/seasons/{seasonId<\d+>}', name: 'season_show', methods: ['GET'])]
    public function showSeason(
        int $programId,
        int $seasonId,
        ProgramRepository $programRepository,
        SeasonRepository $seasonRepository
    ): Response {
        $program = $programRepository->find($programId);
        if (!$program) {
            throw $this->createNotFoundException("Program #$programId introuvable");
        }

        $season = $seasonRepository->find($seasonId);
        if (!$season || $season->getProgram()?->getId() !== $program->getId()) {
            throw $this->createNotFoundException("Season #$seasonId n'appartient pas au Program #$programId");
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season'  => $season,
        ]);
    }

}
