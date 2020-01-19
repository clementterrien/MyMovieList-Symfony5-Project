<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\AddToListType;
use App\Repository\MovielistRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="movies")
     */
    public function movies(MovielistRepository $listRepo, Request $http_request)
    {

        $api_key = $this->getParameter('TMDB_API_KEY');
        $client = HttpClient::create(['http_version' => '2.0']);
        $request = 'https://api.themoviedb.org/3/movie/popular?api_key=' . $api_key . '&language=en-US&page=1';
        $response = $client->request('GET', $request);
        $content = $response->toArray();
        $content = $content['results'];

        foreach ($content as $key => $value) {
            $poster_path = $content[$key]['poster_path'];
            $content[$key]['image'] = 'https://image.tmdb.org/t/p/w200/' . $poster_path;
        }


        if ($http_request->isMethod('get') && $http_request->query->get('list') !== null) {
            $list_query = $http_request->query->get('list');
            $list = $listRepo->findOneBy(['id' => $list_query]);
            $movie_id_url = $http_request->query->get('movie');

            $movie = new Movie();
            $infos = $movie->getInfoInDB($movie_id_url);
            $movie->setAllInfo($infos, $list);

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('movies');
        }

        $all_lists = $listRepo->findAll();

        // if ($data === false) {
        //     var_dump(curl_error($example));
        // }
        // curl_close($example);


        return $this->render('movie/movies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'lists' => $all_lists,
            'movies' => $content,
        ]);
    }

    /**
     * @Route("/showmovie/{id}", name="showmovie")
     */
    public function showMovies($id)
    {
        $api_key = $this->getParameter('TMDB_API_KEY');
        $client = HttpClient::create(['http_version' => '2.0']);
        $request = 'https://api.themoviedb.org/3/movie/' . $id . '?api_key=' . $api_key . '&language=en-US';
        $response = $client->request('GET', $request);
        $content = $response->toArray();
        $poster_path = $content['poster_path'];
        $content['image'] = 'https://image.tmdb.org/t/p/w300/' . $poster_path;
        dump($content);

        // INFORMATION ABOUT THE CAST
        $request = 'https://api.themoviedb.org/3/movie/' . $id . '/credits?api_key=' . $api_key;
        $response = $client->request('GET', $request);
        $credits = $response->toArray();

        $cast = $credits['cast'];
        for ($i = 0; $i <= 5; $i++) {
            $main_characters[$i] = $cast[$i]['name'];
        };

        $crew = $credits['crew'];
        for ($i = 0; $i <= 2; $i++) {
            $directors[$i] = $crew[$i]['name'];
        }

        $movie_info = [
            "title" => $content['title'],
            "image" => $content['image'],
            "release" => $content['release_date'],
            "duration" => $content['runtime'],
            "resume" => $content['overview'],
            "directors" => $directors,
            "cast" => $main_characters,
            'average' => $content['vote_average']
        ];

        // if 

        return $this->render('movie/showMovies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'movie' => $movie_info
        ]);
    }

    /**
     * @Route("/show/{id}{movie}", methods={"GET","HEAD"}, name="showmovie2")
     */
    public function showMovies2($id, $movie1, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $movie = new Movie();
        $list = $request->query->get('list');


        return $this->redirectToRoute('movies');
    }
}
