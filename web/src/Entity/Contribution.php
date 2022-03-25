<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
#[ApiResource(
  //  normalizationContext: ['groups' => ['Contribution:read']],
  //  denormalizationContext: ['groups' => ['Contribution:write']],    
)]
#[ApiFilter(SearchFilter::class, properties: ['techno' => 'exact', 'project' => 'exact', 'member' => 'exact',])]   
class Contribution
{

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Techno::class, inversedBy: 'contributions')] 
    private $techno;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'contributions')]        
    private $project;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'contributions')]             
    private $member;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTechno(): ?Techno
    {
        return $this->techno;
    }

    public function setTechno(?Techno $techno): self
    {
        $this->techno = $techno;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }
}
