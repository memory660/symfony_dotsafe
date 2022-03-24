<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContributionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
#[ApiResource]
class Contribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'contribution', targetEntity: Techno::class)]
    private $techno;

    #[ORM\OneToMany(mappedBy: 'contribution', targetEntity: Project::class)]
    private $project;

    #[ORM\OneToMany(mappedBy: 'contribution', targetEntity: Member::class)]
    private $member;

    public function __construct()
    {
        $this->techno = new ArrayCollection();
        $this->project = new ArrayCollection();
        $this->member = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Techno>
     */
    public function getTechno(): Collection
    {
        return $this->techno;
    }

    public function addTechno(Techno $techno): self
    {
        if (!$this->techno->contains($techno)) {
            $this->techno[] = $techno;
            $techno->setContribution($this);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): self
    {
        if ($this->techno->removeElement($techno)) {
            // set the owning side to null (unless already changed)
            if ($techno->getContribution() === $this) {
                $techno->setContribution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Project $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
            $project->setContribution($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->project->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getContribution() === $this) {
                $project->setContribution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(Member $member): self
    {
        if (!$this->member->contains($member)) {
            $this->member[] = $member;
            $member->setContribution($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->member->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getContribution() === $this) {
                $member->setContribution(null);
            }
        }

        return $this;
    }
}
