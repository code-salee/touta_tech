<?php

namespace App\Entity;

use App\Entity\Personne;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => 'users:read'],
    denormalizationContext:['groups' => 'users:write'],
    routePrefix:"/users",
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
class User extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('users:read')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('users:read', 'users:wrtie')]
    private $statut;

    
    #[ORM\Column(type: 'boolean')]
    #[Groups('users:read', 'users:wrtie')]
    private $isBlocked = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('users:read', 'users:wrtie')]
    private $structure;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Activite::class)]
    #[Groups('users:read')]
    private $activite;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Feedback::class)]
    private $feedback;

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


   
}
