<?php

namespace App\Entity;

use App\Entity\Personne;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['admins:read']],
    denormalizationContext: ['groups' => ['admins:write']],
    routePrefix:"/admins",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ["method" => "POST", "path" => "", "route_name" => "post_admin"]
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ['path'=>'/{id}'],
        'delete' => ['path'=>'/{id}'],
        // 'path' => ['path'=>'/{id}', 'normalization_context' => ['groups' => 'conference:item']]
    ],
    paginationEnabled: false,
    )]
class Admin extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["admins:read"])]
    protected $id;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["admins:read", "admins:write"])]
    private $isblocked = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsblocked(): ?bool
    {
        return $this->isblocked;
    }

    public function setIsblocked(bool $isblocked): self
    {
        $this->isblocked = $isblocked;

        return $this;
    }
}
