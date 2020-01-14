<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"email"},
 * message= "This email is already used !"
 * )
 */
class User implements UserInterface
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Your password must contain at least 8 characters")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Both passwords must be identical")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movielist", mappedBy="User")
     */
    private $movielists;

    public function __construct()
    {
        $this->movielists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getUsername()
    {
    }

    /**
     * @return Collection|Movielist[]
     */
    public function getMovielists(): Collection
    {
        return $this->movielists;
    }

    public function addMovielist(Movielist $movielist): self
    {
        if (!$this->movielists->contains($movielist)) {
            $this->movielists[] = $movielist;
            $movielist->setUser($this);
        }

        return $this;
    }

    public function removeMovielist(Movielist $movielist): self
    {
        if ($this->movielists->contains($movielist)) {
            $this->movielists->removeElement($movielist);
            // set the owning side to null (unless already changed)
            if ($movielist->getUser() === $this) {
                $movielist->setUser(null);
            }
        }

        return $this;
    }
}
