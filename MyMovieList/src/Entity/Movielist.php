<?php

namespace App\Entity;

use App\Entity\Movie;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ListController;
use App\Repository\MovielistRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovielistRepository")
 */
class Movielist
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="movielists")
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="movie_list")
     */
    private $movies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;


    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setMovieInformation(MovielistRepository $repo, $movie_id, $api_key): ?Movie
    {
        $movie = new Movie();
        $user = $this->getUser();

        $client = HttpClient::create(['http_version' => '2.0']);
        $request = 'https://api.themoviedb.org/3/movie/' . $movie_id . '?api_key=' . $api_key . '&language=en-US';
        $response = $client->request('GET', $request);
        $content = $response->toArray();
        $poster_path = $content['poster_path'];
        $content['image'] = 'https://image.tmdb.org/t/p/w300/' . $poster_path;
        dump($content);

        $movie_list = $repo->find(1);

        $movie->setTitle($content['title']);
        $movie->setDuration($content['runtime']);
        $movie->setReleaseAt(new \DateTime($content['release_date']));
        $movie->setSynopsis($content['overview']);
        $movie->setImage($content['image']);
        $movie->setMovieId($content['id']);
        $movie->setMovieList($movie_list);

        return $movie;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->setMovieList($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
            // set the owning side to null (unless already changed)
            if ($movie->getMovieList() === $this) {
                $movie->setMovieList(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
