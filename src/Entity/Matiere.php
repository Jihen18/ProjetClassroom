<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="matieres")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Travail::class, mappedBy="matieres")
     */
    private $travails;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->travails = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setMatieres($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getMatieres() === $this) {
                $category->setMatieres(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Travail[]
     */
    public function getTravails(): Collection
    {
        return $this->travails;
    }

    public function addTravail(Travail $travail): self
    {
        if (!$this->travails->contains($travail)) {
            $this->travails[] = $travail;
            $travail->setMatieres($this);
        }

        return $this;
    }

    public function removeTravail(Travail $travail): self
    {
        if ($this->travails->removeElement($travail)) {
            // set the owning side to null (unless already changed)
            if ($travail->getMatieres() === $this) {
                $travail->setMatieres(null);
            }
        }

        return $this;
    }
}
