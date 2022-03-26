<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
#[ApiResource(
  //  normalizationContext: ['groups' => ['Contribution:read']],
  //  denormalizationContext: ['groups' => ['Contribution:write']],    
)]
#[ApiFilter(SearchFilter::class, properties: ['techno' => 'exact', 'project' => 'exact', 'user' => 'exact',])]   
class Contribution
{

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Techno::class, inversedBy: 'contributions')] 
    #[Assert\NotBlank]       
    private $techno;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'contributions')]     
    #[Assert\NotBlank]          
    private $project;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'contributions')]      
    #[Assert\NotBlank]              
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
