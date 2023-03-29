<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'etat', targetEntity: Sortie::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection|null $sorties = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSorties(): ?Sortie
    {
        return $this->sorties;
    }

    public function setSorties(?Sortie $sortie): self
    {
        $this->sorties = $sortie;

        return $this;
    }
}
