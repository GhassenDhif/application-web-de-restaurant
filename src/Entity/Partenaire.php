<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PartenaireRepository::class)
 * @UniqueEntity("nom")
 */
class Partenaire
{
    /**
     * @ORM\Id
     * @Groups("post:read")
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("post:read")
     * @Assert\Regex(
     * pattern="/\d/",
     * match=false,
     * message="Votre nom ne peut pas contenir des entiers"
     * )
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="nom is required")
     * @Assert\Length(max="10", maxMessage="le {{ limit }} ne doit depasser 10 caractéres")
     * @Assert\Length(min="3", maxMessage="le {{ limit }} doit depasser 3 caractéres")
     */

    private $nom;

    /**
     * @Groups("post:read")
     * @ORM\Column(type="date")
     * @Assert\LessThan("today")
     */
    private $datef;

    /**
     * @Groups("post:read")
     * @ORM\Column(type="string", length=255)
     */
    private $local;

    /**
     * @Groups("post:read")
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Groups("post:read")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="description is required")
     * @Assert\Length(min="8", maxMessage="le nom doit depasser 8 caractéres")
     */
    private $descri;
    /**
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="partenaire")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $categorie;

    protected $captchaCode;


    
    public function getCaptchaCode()
    {
      return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
      $this->captchaCode = $captchaCode;
    }


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

    public function getDatef(): ?\DateTimeInterface
    {
        return $this->datef;
    }

    public function setDatef(\DateTimeInterface $datef): self
    {
        $this->datef = $datef;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(string $local): self
    {
        $this->local = $local;

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

    public function getDescri(): ?string
    {
        return $this->descri;
    }

    public function setDescri(string $descri): self
    {
        $this->descri = $descri;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }




}
