<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type",  type:"string")]
#[UniqueEntity( fields:("email"), message:"L'email doit être unique")]
#[ApiResource(
    collectionOperations: [
        'get' => ["method" => "GET", "path" => "", "route_name" => "get_person", 'normalization_context'=> ['groups' => ['current']]],
        'post' => ['path'=>'']
    ],
    itemOperations: [
        'get' => ['path'=>'/{id}'],
        'put' => ['path'=>'/{id}'],
        'delete' => ['path'=>'/{id}'],
    ],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
    paginationEnabled: false,
    routePrefix: "/personne",
    )]
    
class Personne implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "current", "admin_activites"])]
    protected $id;

    #[ORM\Column(type: 'json')]
    #[Groups(["read", "current"])]
    protected $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le prenom est obligatoire")]
    #[Groups(["read", "write", "current", "feedbacks", "activites", "admin_activites"])]
    protected $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire")]
    #[Groups(["read", "write", "current", "feedbacks", "activites", "admin_activites", "admin_activites"])]
    protected $nom;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message:"Le telephone est obligatoire")]
    #[Groups(["read", "write", "current", "admin_activites", "activites"])]
    protected $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"L'adresse est obligatoire")]
    #[Groups(["read", "write", "current", "admin_activites"])]
    protected $adresse;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'personnes')]
    #[Groups(["read", "write"])]
    protected $role;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"L'email est obligatoire")]
    #[Groups(["read", "write", "current", "admin_activites"])]
    protected $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le mot de passe est obligatoire")]
    #[Groups(["write"])]
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->role->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

   /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
