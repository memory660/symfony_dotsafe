<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TechnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnoRepository::class)]
#[ApiResource]
class Techno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Contribution::class, mappedBy: 'technos')]
    private $contributions;

    public function __construct()
    {
        $this->contributions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Contribution>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContribution(Contribution $contribution): self
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions[] = $contribution;
            $contribution->addTechno($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution): self
    {
        if ($this->contributions->removeElement($contribution)) {
            $contribution->removeTechno($this);
        }

        return $this;
    }
}
