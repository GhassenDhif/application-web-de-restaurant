<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="nom is required")
     * @Assert\Length(max="10", maxMessage="le nom ne doit depasser 10 caractéres")
     * @Assert\Length(min="3", maxMessage="le nom doit depasser 3 caractéres")
     * @Groups ("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups ("post:read")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("post:read")
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="evenement") 
     */
    private $res;

    public function __construct()
    {
        $this->res = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    /**
     * @return Collection<int, Reservation>
     */
    public function getRes(): Collection
    {
        return $this->res;
    }

    public function addRe(Reservation $re): self
    {
        if (!$this->res->contains($re)) {
            $this->res[] = $re;
            $re->setEvenement($this);
        }

        return $this;
    }

    public function removeRe(Reservation $re): self
    {
        if ($this->res->removeElement($re)) {
            // set the owning side to null (unless already changed)
            if ($re->getEvenement() === $this) {
                $re->setEvenement(null);
            }
        }

        return $this;
    }
}
