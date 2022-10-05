<?php

namespace App\Entity;

use App\Repository\CateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert ;

/**
 * @ORM\Entity(repositoryClass=CateRepository::class)
 */
class Cate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cate")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=4)
     * @Groups("cate")
     * @Groups("posts:read")
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Menu", mappedBy="cate" , orphanRemoval=true)
     * @Groups("cate")
     * @Groups("posts:read")
     */
    private $menus;



    public function __construct()
    {
        $this->menus = new ArrayCollection();
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

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setCat($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getCat() === $this) {
                $menu->setCat(null);
            }
        }

        return $this;
    }
}
