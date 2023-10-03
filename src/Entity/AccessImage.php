<?php

namespace App\Entity;

use App\Repository\AccessImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessImageRepository::class)]
class AccessImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Dovetailing::class, mappedBy: 'images')]
    private Collection $dovetailings;

    #[ORM\OneToOne(targetEntity: 'Image', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Image $image = null;

    public function __construct()
    {
        $this->dovetailings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Dovetailing>
     */
    public function getDovetailings(): Collection
    {
        return $this->dovetailings;
    }

    public function addDovetailing(Dovetailing $dovetailing): static
    {
        if (!$this->dovetailings->contains($dovetailing)) {
            $this->dovetailings->add($dovetailing);
            $dovetailing->addImage($this);
        }

        return $this;
    }

    public function removeDovetailing(Dovetailing $dovetailing): static
    {
        if ($this->dovetailings->removeElement($dovetailing)) {
            $dovetailing->removeImage($this);
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): static
    {
        $this->image = $image;

        return $this;
    }
}
