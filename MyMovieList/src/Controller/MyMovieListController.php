<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyMovieListController extends AbstractController
{
    /**
     * @Route("/mymovielist", name="my_movie_list")
     */
    public function index()
    {
        return $this->render('my_movie_list/index.html.twig', [
            'controller_name' => 'MyMovieListController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('my_movie_list/home.html.twig', [
            'controller_name' => 'MyMovieListController',
        ]);
    }

    /**
     * @Route("/movies", name="movies")
     */
    public function movies()
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);

        $Movies = $movieRepo->findAll();

        return $this->render('my_movie_list/movies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'movies' => $Movies
        ]);
    }

    /**
     * @Route("/showmovie", name="showmovie")
     */
    public function showMovies()
    {
        return $this->render('my_movie_list/showMovies.html.twig', [
            'controller_name' => 'MyMovieListController',
        ]);
    }
}
