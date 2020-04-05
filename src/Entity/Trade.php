<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeRepository")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Member", inversedBy="tradeJoins")
     */
    private $memberParticipations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", mappedBy="trades")
     */
    private $items;

    public function __toString()
    {
        return 'Trade n°' . $this->getId();
    }

    public function __construct()
    {
        $this->memberParticipations = new ArrayCollection();
        $this->items = new ArrayCollection();
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
     * @return Collection|Member[]
     */
    public function getMemberParticipations(): Collection
    {
        return $this->memberParticipations;
    }

    public function addMemberParticipation(Member $memberParticipation): self
    {
        if (!$this->memberParticipations->contains($memberParticipation)) {
            $this->memberParticipations[] = $memberParticipation;
        }

        return $this;
    }

    public function removeMemberParticipation(Member $memberParticipation): self
    {
        if ($this->memberParticipations->contains($memberParticipation)) {
            $this->memberParticipations->removeElement($memberParticipation);
        }

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
}
