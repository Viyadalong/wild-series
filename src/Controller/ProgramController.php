<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

/**
* @Route("/programs", name="program_")
*/

class ProgramController extends AbstractController
{
    /**
    * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => 'programs',
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="show", methods={"GET"})
     * @return Response
     */
    public function show(Program $program): Response
    {
        $seasons = $program->getSeasons(); 
        
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program.' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program, 'seasons' => $seasons,
    ]);
    }

    /**
     * Getting a season 
     * @Route("/{program}/seasons/{season}", requirements={"season"="\d+"}, name="season_show", methods={"GET"})
     * @return Response
     */
    public function showSeason(Program $program, Season $season) :Response
    {
        $episodes = $season->getEpisodes(); 

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);

    }

    /**
     * Getting an episode
     *
     * @Route("/{programId}/season/{seasonId}/episode/{episodeId}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode) :Response
    {     
        return $this->render('program/episode_show.html.twig', [
            'program' => $program, 'season' => $season, 'episode' => $episode
        ]);
    }
}
