<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Movielist;
use App\Form\CreateListType;
use App\Form\AddToListType;
use App\Repository\UserRepository;
use App\Repository\MovielistRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListController extends AbstractController
{

    /**
     * @Route("/newMovieList", name="newMovieList")
     */
    public function createMovieList(UserRepository $user, Request $request)
    {
        $list = new Movielist();

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

            return $this->render('list/newMovieList.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('list/newMovieList.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/newMovieList/{movie_id}", name="addtomylist")
     */
    public function addToMyList(MovielistRepository $repo, $movie_id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $api_key = $this->getParameter('TMDB_API_KEY');
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $movie_list = $repo->findOneById(1);
        $movie = $movie_list->setMovieInformation($repo, $movie_id, $api_key);

        $manager->persist($movie);
        $manager->flush();

        return $this->redirectToRoute('movies');
    }

    /**
     * @Route("/mylists", name="showmylists")
     */
    public function showMyLists(MovielistRepository $repo)
    {
        $user = $this->getUser();
        $user_lists = $repo->findAll();
        dump($user_lists);

        return $this->render('list/mylists.html.twig', [
            'lists' => $user_lists
        ]);
    }

    /**
     * @Route("/mylist/{id}", name="showlist")
     */

    public function showList(MovielistRepository $repo, $id, request $http_request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $movie_list = $repo->findOneBy(['id' => $id]);
        $movies = $movie_list->getMovies();

        if (($http_request->isMethod('get') && $http_request->query->get('movie') !== null)) {
            $list = $this->getDoctrine()->getRepository(Movielist::class)->findOneBy(['id' => $http_request->query->get('list')]);
            $movie = $this->getDoctrine()->getRepository(Movie::class)->findOneBy(['id' => $http_request->query->get('movie')]);

            $list->removeMovie($movie);
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($list);
            $manager->remove($movie);
            $manager->flush();

            return $this->redirectToRoute('showlist', ['id' => $id]);
        }


        return $this->render('list/showlist.html.twig', [
            'movies' => $movies,
            'list' => $movie_list
        ]);
    }

    /**
     * @Route("/mylist/remove/{id}", name="removelist")
     */
    public function removeFromList(request $request, $id)
    {
        dump('hello');

        $list_id = '';
        return $this->redirectToRoute('showlist', ['id' => $id]);
        dump($currentRoute);
    }
}
