<?php

namespace App\Entity;

use App\Entity\Personne;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\PersonneController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    attributes: [
        "security" => "is_granted('ROLE_SUPERADMIN') or is_granted('ROLE_ADMIN')",
        "security_message" => "Vous avez pas acces à ce ressource",
        "pagination_items_per_page" => 10
        ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    routePrefix:"/users",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ["method" => "POST", "path" => "", "route_name" => "post_user"]
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ["path" => "/{id}", "controller" => PersonneController::class],
        'path' => ["method" => "PATCH", "path" => "/{id}/etat", "route_name" => "refusé_user"],
        'delete' => ['path'=>'/{id}'],
    ],
    paginationEnabled: false,
    )]
class User extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "write", "current"])]
    #[Assert\NotBlank(message:"Le statut est obligatoire")]
    private $statut;

    
    #[ORM\Column(type: 'boolean')]
    #[Groups(["read", "write", "current"])]
    private $isBlocked = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read", "write", "current"])]
    #[Assert\NotBlank(message:"Le structure est obligatoire")]
    private $structure;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Activite::class)]
    #[Groups(["read", "current"])]
    private $activite;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Feedback::class)]
    #[Groups(["activites"])]
    private $feedback;

    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'users')]
    #[Groups(["read"])]
    private $admins;

    #[ORM\ManyToOne(targetEntity: Metier::class, inversedBy: 'users')]
    #[Groups(["read"])]
    private $metier;

    public function __construct()
    {
        $this->activite = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    

    public function isIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(?string $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivite(): Collection
    {
        return $this->activite;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activite->contains($activite)) {
            $this->activite[] = $activite;
            $activite->setUser($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activite->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getUser() === $this) {
                $activite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback[] = $feedback;
            $feedback->setUser($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getUser() === $this) {
                $feedback->setUser(null);
            }
        }

        return $this;
    }

    public function getAdmins(): ?Admin
    {
        return $this->admins;
    }

    public function setAdmins(?Admin $admins): self
    {
        $this->admins = $admins;

        return $this;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): self
    {
        $this->metier = $metier;

        return $this;
    }


   
}
