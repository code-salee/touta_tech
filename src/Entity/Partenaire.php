<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PartenaireRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => 'partenaires:read'],
    denormalizationContext:['groups' => 'partenaires:write'],
    routePrefix:"/partenaires",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ['path'=>'']
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ['path'=>'/{id}'],
        'delete' => ['path'=>'/{id}'],
    ],
    paginationEnabled: false,
    )]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read"])]
    private $nom;

    #[ORM\Column(type: 'string')]
    #[Groups(["read"])]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read"])]
    private $adresse;

    #[ORM\ManyToOne(targetEntity: Superadmin::class, inversedBy: 'partenaires')]
    private $superadmin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSuperadmin(): ?Superadmin
    {
        return $this->superadmin;
    }

    public function setSuperadmin(?Superadmin $superadmin): self
    {
        $this->superadmin = $superadmin;

        return $this;
    }
}
