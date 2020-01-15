<?php

namespace App\Controller;

use App\Entity\Listitem;
use App\Entity\Movielist;
use App\Form\CreateListType;
use App\Repository\UserRepository;
use App\Repository\ListitemRepository;
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

            return $this->render('my_movie_list/NewMovieList.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('list/NewMovieList.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/newMovieList/{movie_id}", name="addtomylist")
     */
    public function addToMyList(MovielistRepository $repo, ListitemRepository $list_Item, $movie_id)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $movie_list = $repo->find(1);
        dump($movie_list);

        $list_Item = new Listitem();

        $list_Item->setMovielist($movie_list);
        $list_Item->setMovieId($movie_id);

        $manager->persist($list_Item);
        $manager->flush();

        return $this->render('list/NewMovieList.html.twig');
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

    public function showList(MovielistRepository $repo, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $movie_list = $repo->find($id);
        $movies = $movie_list->getListitems();
        dump($movies);

        $api_key = $this->getParameter('TMDB_API_KEY');
        $client = HttpClient::create(['http_version' => '2.0']);
        $request = 'https://api.themoviedb.org/3/movie/' . $id . '?api_key=' . $api_key . '&language=en-US';
        $response = $client->request('GET', $request);
        $content = $response->toArray();

        return $this->render('list/showlist.html.twig', [
            'movies' => $movies,
            'list' => $movie_list
        ]);
    }
}
