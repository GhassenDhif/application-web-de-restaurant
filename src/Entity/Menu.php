<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */

class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("menu")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("menu")
     * @Groups("posts:read")
     */
    private $nom;



    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("menu")
     * @Groups("posts:read")
     */
    private $descp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("menu")
     * @Groups("posts:read")
     
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Groups("menu")
     * @Groups("posts:read")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cate", inversedBy="Menu")
     * @Groups("menu")
     * @Groups("posts:read")
     */
    private $cat;

    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="menu_prefere")
     * @Groups("menu")
     * @Groups("posts:read")
     */
    private $client;



    public function __construct()
    {
        $this->client = new ArrayCollection();
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

    public function getDescp(): ?string
    {
        return $this->descp;
    }

    public function setDescp(string $descp): self
    {
        $this->descp = $descp;

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCat(): ?Cate
    {
        return $this->cat;
    }

    public function setCat(?Cate $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * @return Collection<int, client>
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
            $client->setMenuPrefere($this);
        }

        return $this;
    }

    public function removeClient(client $client): self
    {
        if ($this->client->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getMenuPrefere() === $this) {
                $client->setMenuPrefere(null);
            }
        }

        return $this;
    }



}
