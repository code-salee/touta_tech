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
        'gtActivites' => ['path' => '/{id}/activites', 'method' => 'GET', 'normalization_context' => ['groups' => ['superadmin_activites']] ],
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

    #[ORM\OneToMany(mappedBy: 'superadmin', targetEntity: Activite::class)]
    #[Groups(['superadmin_activites'])]
    private $activites;

    #[ORM\Column(type: 'boolean')]
    private $isblocked = false;

    public function __construct()
    {
        $this->partenaires = new ArrayCollection();
        $this->activites = new ArrayCollection();
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

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setSuperadmin($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getSuperadmin() === $this) {
                $activite->setSuperadmin(null);
            }
        }

        return $this;
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
