<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\MovieController;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $synopsis;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $movie_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movielist", inversedBy="movies")
     */
    private $movie_list;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getReleaseAt(): ?\DateTimeInterface
    {
        return $this->releaseAt;
    }

    public function setReleaseAt(\DateTimeInterface $releaseAt): self
    {
        $this->releaseAt = $releaseAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMovieId(): ?int
    {
        return $this->movie_id;
    }

    public function setMovieId(int $movie_id): self
    {
        $this->movie_id = $movie_id;

        return $this;
    }

    public function getMovieList(): ?Movielist
    {
        return $this->movie_list;
    }

    public function setMovieList(?Movielist $movie_list): self
    {
        $this->movie_list = $movie_list;

        return $this;
    }

    public function setAllInfo(array $infos, $movie_list)
    {
        $this->setTitle($infos['title']);
        $this->setImage($infos['image']);
        $this->setSynopsis($infos['overview']);
        $this->setReleaseAt(new \DateTime($infos['release_date']));
        $this->setMovieList($movie_list);
        $this->setDuration($infos['runtime']);
        $this->setMovieId($infos['id']);
    }

    public function getInfoInDB($movie_id)
    {
        $api_key = "33d05c8e37247f33afa3107d85157dad";
        $client = HttpClient::create(['http_version' => '2.0']);
        $request = 'https://api.themoviedb.org/3/movie/' . $movie_id . '?api_key=' . $api_key . '&language=en-US';
        $response = $client->request('GET', $request);
        $query_movie = $response->toArray();
        $poster_path = $query_movie['poster_path'];
        $query_movie['image'] = 'https://image.tmdb.org/t/p/200/' . $poster_path;

        return $query_movie;
    }
}
