<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FeedbackRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_items_per_page" => 10
        ],
    normalizationContext:['groups' => 'feedbacks'],
    denormalizationContext:['groups' => 'feedbacks'],
    routePrefix:"/feedbacks",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ["method" => "POST", "path" => "", "route_name" => "post_feedback"]
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ['path'=>'/{id}'],
        'delete' => ['path'=>'/{id}'],
    ],
    paginationEnabled: false,
    )]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "feedbacks", "activites", "user_feedbacks", "admin_activites"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "feedbacks", "activites", "user_feedbacks", "admin_activites"])]
    #[Assert\NotBlank(message:"Le commentaire est obligatoire")]
    private $libelle;

    #[ORM\ManyToOne(targetEntity: Activite::class, inversedBy: 'feedback')]
    private $activite;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'feedback')]
    #[Groups(["feedbacks", "admin_activites"])]
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
