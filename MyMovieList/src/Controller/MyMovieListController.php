<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
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
    public function movies(MovieRepository $movieRepo)
    {
        $Movies = $movieRepo->findAll();
        dump($Movies);

        return $this->render('my_movie_list/movies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'movies' => $Movies
        ]);
    }

    /**
     * @Route("/showmovie/{id}", name="showmovie")
     */
    public function showMovies(MovieRepository $repo, $id)
    {
        $movies = $repo->find($id);

        return $this->render('my_movie_list/showMovies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/newMovieList", name="newMovieList")
     */
    public function createMovieList(Request $request)
    {
        dump($request);
        return $this->render('my_movie_list/newMovieList.html.twig');
    }
}
