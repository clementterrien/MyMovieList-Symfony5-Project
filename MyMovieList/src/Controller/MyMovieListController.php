<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('my_movie_list/movies.html.twig', [
            'controller_name' => 'MyMovieListController',
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
