<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $nbBenevoles = null;

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getLieu(): ?string { return $this->lieu; }
    public function setLieu(string $lieu): static { $this->lieu = $lieu; return $this; }
    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }
    public function getNbBenevoles(): ?int { return $this->nbBenevoles; }
    public function setNbBenevoles(int $nbBenevoles): static { $this->nbBenevoles = $nbBenevoles; return $this; }
}
