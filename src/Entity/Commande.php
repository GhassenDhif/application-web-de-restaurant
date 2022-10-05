<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
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
     * @Assert\NotBlank(message="missing_title");
     */
    private $titre;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $date_de_commande;

    /**
     * @ORM\Column(type="float", precision=6, scale=3)
     * @Groups("post:read")
     * @Assert\Range(min=2, max=3000);
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class)
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDeCommande(): ?\DateTimeInterface
    {
        return $this->date_de_commande;
    }

    public function setDateDeCommande(\DateTimeInterface $date_de_commande): self
    {
        $this->date_de_commande = $date_de_commande;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }



}
