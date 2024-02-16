<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut_coursier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut_client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_livraison = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $intervalle_temp = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Colis::class)]
    private Collection $relation;

    public function __construct()
    {
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatutCoursier(): ?string
    {
        return $this->statut_coursier;
    }

    public function setStatutCoursier(?string $statut_coursier): static
    {
        $this->statut_coursier = $statut_coursier;

        return $this;
    }

    public function getStatutClient(): ?string
    {
        return $this->statut_client;
    }

    public function setStatutClient(?string $statut_client): static
    {
        $this->statut_client = $statut_client;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->date_livraison;
    }

    public function setDateLivraison(\DateTimeInterface $date_livraison): static
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getIntervalleTemp(): ?\DateTimeInterface
    {
        return $this->intervalle_temp;
    }

    public function setIntervalleTemp(\DateTimeInterface $intervalle_temp): static
    {
        $this->intervalle_temp = $intervalle_temp;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Colis>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Colis $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
            $relation->setLivraison($this);
        }

        return $this;
    }

    public function removeRelation(Colis $relation): static
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getLivraison() === $this) {
                $relation->setLivraison(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }

}
