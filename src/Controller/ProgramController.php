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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchProgramType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/programs', name: 'program_')]
class ProgramController extends AbstractController
{
 #[Route('/', name: 'index')]
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('search')->getData();

            if ($search !== null && $search !== '') {
                $programs = $programRepository->findByTitleOrActorName($search);
            } else {
                $programs = $programRepository->findAll();
            }
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->render('program/index.html.twig', [
            'website'  => 'Wild Series',
            'programs' => $programs,
            'form'     => $form->createView(),
        ]);
    }


    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug((string) $program->getTitle())->lower();
            $program->setSlug($slug);

            $program->setOwner($this->getUser());

            $em->persist($program);
            $em->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('admin@example.com') 
                ->subject('Une nouvelle série vient d’être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', [
                    'program' => $program,
            ]));

            $mailer->send($email);

            $this->addFlash('success', 'Program created!');
            return $this->redirectToRoute('program_show', ['slug' => $program->getSlug()]);
        }

        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id<\d+>}/watchlist', name: 'watchlist', methods: ['GET', 'POST'])]
    public function watchlist(Program $program, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($user->getWatchlist()->contains($program)) {
            $user->removeWatchlist($program);
        } else {
            $user->addWatchlist($program);
        }

        $em->flush();

        return $this->json([
            'isInWatchlist' => $user->getWatchlist()->contains($program),
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

        if (
            !$this->isGranted('ROLE_ADMIN') &&
            $this->getUser() !== $program->getOwner()
        ) {
            throw $this->createAccessDeniedException(
                'Vous ne pouvez modifier que vos propres séries.'
            );
        }
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
        #[MapEntity(mapping: ['episodeSlug' => 'slug'])] Episode $episode,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if ($season->getProgram() !== $program || $episode->getSeason() !== $season) {
            throw $this->createNotFoundException('Incohérence programme/saison/épisode.');
        }

        $comments = $episode->getComments()->toArray();
        usort($comments, fn ($a, $b) => $a->getCreatedAt() <=> $b->getCreatedAt());

        $commentFormView = null;

        if ($this->getUser()) {
            $comment = new Comment();
            
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            
            
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $comment->setEpisode($episode);
                $comment->setAuthor($this->getUser());
                $comment->setCreatedAt(new \DateTimeImmutable());

                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Commentaire ajouté !');

                return $this->redirectToRoute('program_episode_show', [
                    'programSlug' => $program->getSlug(),
                    'seasonId' => $season->getId(),
                    'episodeSlug' => $episode->getSlug(),
                ]);
            }
            $commentFormView = $form->createView();
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season'  => $season,
            'episode' => $episode,
            'comments' => $episode->getComments(),
            'commentForm' => $commentFormView,
        ]);
    }
    #[Route('/comments/{id}/delete', name: 'comment_delete', methods: ['POST'])]
        public function deleteComment(
            Comment $comment,
            Request $request,
            EntityManagerInterface $em
        ): Response {

            $episode = $comment->getEpisode();
            $season  = $episode->getSeason();
            $program = $season->getProgram();


            if (!$this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
                $this->addFlash('danger', 'Token CSRF invalide.');
            } else {
                $user = $this->getUser();

                if (
                    !$this->isGranted('ROLE_ADMIN')
                    && (!$user || $user !== $comment->getAuthor())
                ) {
                    throw $this->createAccessDeniedException(
                        'Vous ne pouvez supprimer que vos propres commentaires.'
                    );
                }

                $em->remove($comment);
                $em->flush();

                $this->addFlash('success', 'Commentaire supprimé.');
            }

            return $this->redirectToRoute('program_episode_show', [
                'programSlug' => $program->getSlug(),
                'seasonId'    => $season->getId(),
                'episodeSlug' => $episode->getSlug(),
            ]);
        }
    }
