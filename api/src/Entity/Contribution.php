<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Ramsey\Uuid\Doctrine\UuidGenerator\UuidOrderedTimeGenerator;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
#[ApiResource(
  //  normalizationContext: ['groups' => ['Contribution:read']],
  //  denormalizationContext: ['groups' => ['Contribution:write']],    
)]
class Contribution
{

    #[ORM\Id]
    #[ORM\Column(type:"uuid", unique:true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "App\Util\Doctrine\UuidIdGenerator")]
    protected $id;

    #[ORM\ManyToOne(targetEntity: Techno::class, inversedBy: 'contributions')]
    //#[Groups(["Contribution:read", "Contribution:write"])]  
    #[ApiProperty(identifier: true)]    
    //#[ApiSubresource()]         
    private $techno;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'contributions')]
    //#[Groups(["Contribution:read", "Contribution:write"])]       
    #[ApiProperty(identifier: true)]     
    //#[ApiSubresource()]          
    private $project;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'contributions')]
    //#[Groups(["Contribution:read", "Contribution:write"])]      
    #[ApiProperty(identifier: true)]     
    //#[ApiSubresource()]            
    private $member;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?string
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
