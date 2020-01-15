<?php

namespace App\Controller;

use App\Entity\ListItem;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\MovieList;
use App\Form\CreateListType;
use App\Form\RegistrationType;
use App\Repository\MovieListRepository;
use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $api_key = $this->getParameter('TMDB_API_KEY');
        $client = HttpClient::create(['http_version' => '2.0']);
        $request = 'https://api.themoviedb.org/3/movie/popular?api_key=' . $api_key . '&language=en-US&page=1';
        $response = $client->request('GET', $request);
        $content = $response->toArray();
        $content = $content['results'];
        dump($content);

        // $content[0]['image'] = 'hello';

        foreach ($content as $key => $value) {
            $poster_path = $content[$key]['poster_path'];
            $content[$key]['image'] = 'https://image.tmdb.org/t/p/w200/' . $poster_path;
        }

        // if ($data === false) {
        //     var_dump(curl_error($example));
        // }
        // curl_close($example);

        return $this->render('my_movie_list/movies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'movies' => $content
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

        return $this->render('my_movie_list/showMovies.html.twig', [
            'controller_name' => 'MyMovieListController',
            'movie' => $movie_info
        ]);
    }

    /**
     * @Route("/random", name="random")
     */
    public function testAPI(Request $request)
    {
        return $this->render('my_movie_list/newMovieList.html.twig');
    }

    /**
     * @Route("/myprofile", name="myprofile") 
     */
    public function myProfile(UserRepository $userRepo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $user_id = $user->getId();
        $modify_user = [
            "firstname" => 0,
            "lastname" => 0,
            "email" => 0,
            "password" => 0,
        ];

        $userDB = $userRepo->find($user_id);
        return $this->render('my_movie_list/myprofile.html.twig', [
            'user' => $userDB,
            'modify' => $modify_user
        ]);
    }

    /**
     * @Route("/myprofile/modify", name="modifymyprofile") 
     */
    public function modifyMyProfile(UserRepository $userRepo, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $user_id = $user->getId();
        $userDB = $userRepo->find($user_id);

        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('my_movie_list/modifyprofile.html.twig', [
            'form' => $form->createView(),
            'userDB' => $userDB
        ]);
    }


    /**
     * @Route("/newMovieList", name="newMovieList")
     */
    public function createMovieList(UserRepository $user, Request $request)
    {
        $list = new MovieList();

        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CreateListType::class, $list);
        dump($form);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $list->setUser($user);

            $user_lists = 'hello';

            $manager->persist($list);
            $manager->flush();

            return $this->render('my_movie_list/NewMovieList.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('my_movie_list/NewMovieList.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/newMovieList/{movie_id}", name="addtomylist")
     */
    public function addToMyList($movie_id)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $manager = $this->getDoctrine()->getManager();

        $list_Item = 'h';




        $manager->persist($list_Item);
        $manager->flush();

        return $this->render('my_movie_list/NewMovieList.html.twig');
    }
}
