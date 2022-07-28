<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
#[ApiResource(
    attributes: [
        "pagination_items_per_page" => 10
        ],
    normalizationContext: ['groups' => ['activites']],
    denormalizationContext: ['groups' => ['activites']],
    routePrefix:"/activites",
    collectionOperations: [
        'get' => ['path'=>''],
        'post' => ["method" => "POST", "path" => "", "route_name" => "post_activite"]
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ['path'=>'/{id}'],
        'delete' => ['path'=>'/{id}'],
    ],
    paginationEnabled: false,
    )]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "activites", "admin_activites", "user_feedbacks", 'superadmin_activites'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "activites", "admin_activites", "user_feedbacks", 'superadmin_activites'])]
    #[Assert\NotBlank(message:"Le description est obligatoire")]
    private $description;

    #[ORM\Column(type: 'date')]
    #[Groups(["read", "activites", "admin_activites", "user_feedbacks", 'superadmin_activites'])]
    #[Assert\NotBlank(message:"La date est obligatoire")]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read", "activites", "admin_activites", "user_feedbacks", 'superadmin_activites'])]
    #[Assert\NotBlank(message:"Le lieu est obligatoire")]
    private $lieu;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'activite')]
    #[Groups(["activites", "admin_activites", 'superadmin_activites'])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'activite', targetEntity: Feedback::class)]
    #[Groups(["read", "admin_activites", "user_feedbacks", 'superadmin_activites'])]
    private $feedback;

    #[Groups(["activites"])]
    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'activite')]
    private $admin;

    #[Groups(["read", "activites", "admin_activites", "user_feedbacks"])]
    #[ORM\Column(type: 'integer')]
    private $etat = 1;

    #[ORM\ManyToOne(targetEntity: Superadmin::class, inversedBy: 'activites')]
    private $superadmin;

    public function __construct()
    {
        $this->feedback = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

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
            $feedback->setActivite($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getActivite() === $this) {
                $feedback->setActivite(null);
            }
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

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
