<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FeedbackRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => 'feedbacks:read'],
    denormalizationContext:['groups' => 'feedbacks:write'],
    routePrefix:"/feedbacks",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ['path'=>'']
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ['path'=>'/{id}'],
        'delete' => ['path'=>'/{id}'],
        // 'path' => ['path'=>'/{id}', 'normalization_context' => ['groups' => 'conference:item']]
    ],
    paginationEnabled: false,
    )]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["users:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["users:read"])]
    private $libelle;

    #[ORM\ManyToOne(targetEntity: Activite::class, inversedBy: 'feedback')]
    private $activite;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'feedback')]
    private $user;


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

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): self
    {
        $this->activite = $activite;

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
