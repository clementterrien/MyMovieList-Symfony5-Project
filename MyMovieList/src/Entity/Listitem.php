<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListitemRepository")
 */
class Listitem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $movie_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movielist", inversedBy="listitems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movielist;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMovielist(): ?Movielist
    {
        return $this->movielist;
    }

    public function setMovielist(?Movielist $movielist): self
    {
        $this->movielist = $movielist;

        return $this;
    }
}
