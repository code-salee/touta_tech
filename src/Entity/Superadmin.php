<?php

namespace App\Entity;

use App\Entity\Personne;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\PersonneController;
use App\Repository\SuperadminRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SuperadminRepository::class)]
#[ApiResource(
    attributes: [
        "security" => "is_granted('ROLE_SUPERADMIN')",
        "security_message" => "Vous avez pas acces à ce ressource",
        "pagination_items_per_page" => 10
        ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    routePrefix:"/superadmins",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ["method" => "POST", "path" => "", "route_name" => "post_super_admin"]
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ["path" => "/{id}", "controller" => PersonneController::class],
        'delete' => ['path'=>'/{id}'],
    ],
    paginationEnabled: false,
    )]
class Superadmin extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\OneToMany(mappedBy: 'superadmin', targetEntity: Partenaire::class)]
    #[Groups(["read"])]
    private $partenaires;

    public function __construct()
    {
        $this->partenaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Partenaire>
     */
    public function getPartenaires(): Collection
    {
        return $this->partenaires;
    }

    public function addPartenaire(Partenaire $partenaire): self
    {
        if (!$this->partenaires->contains($partenaire)) {
            $this->partenaires[] = $partenaire;
            $partenaire->setSuperadmin($this);
        }

        return $this;
    }

    public function removePartenaire(Partenaire $partenaire): self
    {
        if ($this->partenaires->removeElement($partenaire)) {
            // set the owning side to null (unless already changed)
            if ($partenaire->getSuperadmin() === $this) {
                $partenaire->setSuperadmin(null);
            }
        }

        return $this;
    }
}
