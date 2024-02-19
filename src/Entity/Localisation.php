<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalisationRepository::class)]
class Localisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;  // Corrected property name to $longitude

    #[ORM\OneToMany(mappedBy: 'localisation', targetEntity: Livraison::class)]
    private Collection $relation;

    public function __construct()
    {
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static  // Corrected method name to setLongitude
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Livraison>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Livraison $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
            $relation->setLocalisation($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function removeRelation(Livraison $relation): static
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getLocalisation() === $this) {
                $relation->setLocalisation(null);
            }
        }

        return $this;
    }
}

