<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé !")
 */
class Member implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max=255)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(
     *      max = 12,
     *      min = 12,
     *      maxMessage = "le code ami doit contenir 12 chiffres",
     *      minMessage = "le code ami doit 12 chiffres"
     * )
     */
    private $switchCode = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trade", mappedBy="member")
     */
    private $trades;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TradeMemberParticipation", mappedBy="member", orphanRemoval=true)
     */
    private $tradeMemberParticipations;

    public function __toString()
    {
        return $this->getUsername();
    }

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->trades = new ArrayCollection();
        $this->tradeMemberParticipations = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string) $this->username;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
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

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSwitchCode(): ?string
    {
        return $this->switchCode;
    }

    public function setSwitchCode(?string $switchCode): self
    {
        $this->switchCode = $switchCode;

        return $this;
    }

    /**
     * @return Collection|Trade[]
     */
    public function getTrades(): Collection
    {
        return $this->trades;
    }

    public function addTrade(Trade $trade): self
    {
        if (!$this->trades->contains($trade)) {
            $this->trades[] = $trade;
            $trade->setMember($this);
        }

        return $this;
    }

    public function removeTrade(Trade $trade): self
    {
        if ($this->trades->contains($trade)) {
            $this->trades->removeElement($trade);
            // set the owning side to null (unless already changed)
            if ($trade->getMember() === $this) {
                $trade->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TradeMemberParticipation[]
     */
    public function getTradeMemberParticipations(): Collection
    {
        return $this->tradeMemberParticipations;
    }

    public function addTradeMemberParticipation(TradeMemberParticipation $tradeMemberParticipation): self
    {
        if (!$this->tradeMemberParticipations->contains($tradeMemberParticipation)) {
            $this->tradeMemberParticipations[] = $tradeMemberParticipation;
            $tradeMemberParticipation->setMember($this);
        }

        return $this;
    }

    public function removeTradeMemberParticipation(TradeMemberParticipation $tradeMemberParticipation): self
    {
        if ($this->tradeMemberParticipations->contains($tradeMemberParticipation)) {
            $this->tradeMemberParticipations->removeElement($tradeMemberParticipation);
            // set the owning side to null (unless already changed)
            if ($tradeMemberParticipation->getMember() === $this) {
                $tradeMemberParticipation->setMember(null);
            }
        }

        return $this;
    }
}
