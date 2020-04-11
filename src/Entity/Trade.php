<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeRepository")
 * @App\Validator\TradeClass
 */
class Trade
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(5)
     * @Assert\Regex(pattern="/[A-Z0-9 ]+/", match=true, message="Majuscule et chiffre seulement autorisé")
     */
    private $dodoCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="trades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", mappedBy="trades")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TradeMemberParticipation", mappedBy="trade", orphanRemoval=true)
     */
    private $tradeMemberParticipations;

    public function __toString()
    {
        return 'TradeClass n°' . $this->getId();
    }

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->tradeMemberParticipations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDodoCode(): ?string
    {
        return $this->dodoCode;
    }

    public function setDodoCode(?string $dodoCode): self
    {
        $this->dodoCode = $dodoCode;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addTrade($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->removeTrade($this);
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
            $tradeMemberParticipation->setTrade($this);
        }

        return $this;
    }

    public function removeTradeMemberParticipation(TradeMemberParticipation $tradeMemberParticipation): self
    {
        if ($this->tradeMemberParticipations->contains($tradeMemberParticipation)) {
            $this->tradeMemberParticipations->removeElement($tradeMemberParticipation);
            // set the owning side to null (unless already changed)
            if ($tradeMemberParticipation->getTrade() === $this) {
                $tradeMemberParticipation->setTrade(null);
            }
        }

        return $this;
    }
}
