<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MetierRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MetierRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => 'metiers:read'],
    denormalizationContext:['groups' => 'metiers:write'],
    routePrefix:"/metiers",
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
    
class Metier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\OneToMany(mappedBy: 'metier', targetEntity: User::class)]
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setMetier($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMetier() === $this) {
                $user->setMetier(null);
            }
        }

        return $this;
    }
}
