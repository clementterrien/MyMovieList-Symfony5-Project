<?php

namespace App\Controller;

use App\Repository\MovielistRepository;
use Symfony\Bridge\Twig\Node\DumpNode;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, MovielistRepository $listRepo)
    {
        dump($request);

        $keyword = $request->request->get('search');
        if (str_word_count($keyword) > 1) {
            $keyword = str_replace(' ', '+', $keyword);
            dump('change', $keyword);
        }

        $api_key = $this->getParameter('TMDB_API_KEY');
        $client = HttpClient::create(['http_version' => '2.0']);
        $query = "https://api.themoviedb.org/3/search/movie?api_key=" . $api_key . "&query=" . $keyword;
        $response = $client->request('GET', $query);
        $content = $response->toArray();
        $content = $content['results'];

        foreach ($content as $key => $value) {
            $poster_path = $content[$key]['poster_path'];
            $content[$key]['image'] = 'https://image.tmdb.org/t/p/w200/' . $poster_path;
        }

        $all_lists = $listRepo->findAll();

        return $this->render('search/search.html.twig', [
            'lists' => $all_lists,
            'movies' => $content,
        ]);
    }
}
