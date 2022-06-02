<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

// /**
//  * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
//  */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet e-mail')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Le champ e-mail est requis.')]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champ prénom est requis.')]
    #[Assert\Regex(pattern: '/^[a-z]+$/i', htmlPattern: '^[a-zA-Z]+$')]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champ nom est requis.')]
    #[Assert\Regex(pattern: '/^[a-z]+$/i', htmlPattern: '^[a-zA-Z]+$')]
    private $last_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $avatar;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $couverture;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommandShop::class, orphanRemoval: true)]
    private $commandShops;

    #[ORM\Column(type: 'boolean')]
    private $isConfirmed = false;

    #[ORM\Column(type: 'string', length: 255)]
    private $tokenConfirmationEmail;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $tokenPasswordLost;

    public function __construct()
    {
        $this->commandShops = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(?string $couverture): self
    {
        $this->couverture = $couverture;

        return $this;
    }

    /**
     * @return Collection|CommandShop[]
     */
    public function getCommandShops(): Collection
    {
        return $this->commandShops;
    }

    public function addCommandShop(CommandShop $commandShop): self
    {
        if (!$this->commandShops->contains($commandShop)) {
            $this->commandShops[] = $commandShop;
            $commandShop->setUser($this);
        }

        return $this;
    }

    public function removeCommandShop(CommandShop $commandShop): self
    {
        if ($this->commandShops->removeElement($commandShop)) {
            // set the owning side to null (unless already changed)
            if ($commandShop->getUser() === $this) {
                $commandShop->setUser(null);
            }
        }

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getTokenConfirmationEmail(): ?string
    {
        return $this->tokenConfirmationEmail;
    }

    public function setTokenConfirmationEmail(string $tokenConfirmationEmail): self
    {
        $this->tokenConfirmationEmail = $tokenConfirmationEmail;

        return $this;
    }

    public function getTokenPasswordLost(): ?string
    {
        return $this->tokenPasswordLost;
    }

    public function setTokenPasswordLost(?string $tokenPasswordLost): self
    {
        $this->tokenPasswordLost = $tokenPasswordLost;

        return $this;
    }

}
