<?php

namespace App\Entity;

use App\Entity\Personne;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use App\Controller\PersonneController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ApiResource(
    attributes: [
        "security" => "is_granted('ROLE_SUPERADMIN') || is_granted('ROLE_ADMIN')",
        "security_message" => "Vous avez pas acces à ce ressource",
        "pagination_items_per_page" => 10
        ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    routePrefix:"/admins",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ["method" => "POST", "path" => "", "route_name" => "post_admin"]
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'get' => ['path'=>'/{id}/activites',  'normalization_context'=> ['groups' => ['admin_activites']]],
        'put' => ["path" => "/{id}", "controller" => PersonneController::class],
        'delete' => ['path'=>'/{id}'],
    ],
    paginationEnabled: false,
    )]
class Admin extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["read", "write", "current"])]
    private $isblocked = false;

    #[ORM\OneToMany(mappedBy: 'admins', targetEntity: User::class)]
    private $users;

    #[Groups(["read", "write", "current", "admin_activites"])]
    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Activite::class)]
    private $activite;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->activite = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAdmins($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAdmins() === $this) {
                $user->setAdmins(null);
            }
        }

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
            $activite->setAdmin($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activite->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getAdmin() === $this) {
                $activite->setAdmin(null);
            }
        }

        return $this;
    }
}
