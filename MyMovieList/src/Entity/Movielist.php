<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Listitem", mappedBy="movielist")
     */
    private $listitems;

    public function __construct()
    {
        $this->listitems = new ArrayCollection();
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

    /**
     * @return Collection|Listitem[]
     */
    public function getListitems(): Collection
    {
        return $this->listitems;
    }

    public function addListitem(Listitem $listitem): self
    {
        if (!$this->listitems->contains($listitem)) {
            $this->listitems[] = $listitem;
            $listitem->setMovielist($this);
        }

        return $this;
    }

    public function removeListitem(Listitem $listitem): self
    {
        if ($this->listitems->contains($listitem)) {
            $this->listitems->removeElement($listitem);
            // set the owning side to null (unless already changed)
            if ($listitem->getMovielist() === $this) {
                $listitem->setMovielist(null);
            }
        }

        return $this;
    }
}
