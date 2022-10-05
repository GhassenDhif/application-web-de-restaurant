<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 */
class Personne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $prenom;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $mot_pass;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $adress_email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $role ;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMotPass(): ?string
    {
        return $this->mot_pass;
    }

    public function setMotPass(string $mot_pass): self
    {
        $this->mot_pass = $mot_pass;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAdressEmail(): ?string
    {
        return $this->adress_email;
    }

    public function setAdressEmail(string $adress_email): self
    {
        $this->adress_email = $adress_email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
