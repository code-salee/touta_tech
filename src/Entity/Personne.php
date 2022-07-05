<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type",  type:"string")]
#[UniqueEntity( fields:("email"), message:"L'email doit être unique")]

class Personne implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["superadmins:read", "admins:read", "users:read"])]
    protected $id;

    #[ORM\Column(type: 'json')]
    #[Groups(["superadmins:read", "admins:read", "users:read"])]
    protected $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le prenom est obligatoire")]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
    protected $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire")]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
    protected $nom;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message:"Le telephone est obligatoire")]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
    protected $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"L'adresse est obligatoire")]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
    protected $adresse;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'personnes')]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
    protected $role;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"L'email est obligatoire")]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
    protected $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le mot de passe est obligatoire")]
    #[Groups(["superadmins:read", "superadmins:write", "admins:read", "admins:write", "users:read", "users:write",])]
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
    public function getUserIdentifier()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
