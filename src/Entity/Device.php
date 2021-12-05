<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 */
class Device
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=House::class, inversedBy="devices")
     */
    private $house;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $make;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wattage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $voltage;

    /**
     * @ORM\OneToMany(targetEntity=Usage::class, mappedBy="device")
     */
    private $usages;

    /**
     * @ORM\ManyToOne(targetEntity=DeviceType::class, inversedBy="devices")
     */
    private $deviceType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priority_rating;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $plugged_in;

    public function __construct()
    {
        $this->plugged_in = false;
        $this->usages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHouse(): ?House
    {
        return $this->house;
    }

    public function setHouse(?House $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(?string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getWattage(): ?string
    {
        return $this->wattage;
    }

    public function setWattage(?string $wattage): self
    {
        $this->wattage = $wattage;

        return $this;
    }

    public function getVoltage(): ?string
    {
        return $this->voltage;
    }

    public function setVoltage(?string $voltage): self
    {
        $this->voltage = $voltage;

        return $this;
    }

    /**
     * @return Collection|Usage[]
     */
    public function getUsages(): Collection
    {
        return $this->usages;
    }

    public function addUsage(Usage $usage): self
    {
        if (!$this->usages->contains($usage)) {
            $this->usages[] = $usage;
            $usage->setDevice($this);
        }

        return $this;
    }

    public function removeUsage(Usage $usage): self
    {
        if ($this->usages->removeElement($usage)) {
            // set the owning side to null (unless already changed)
            if ($usage->getDevice() === $this) {
                $usage->setDevice(null);
            }
        }

        return $this;
    }

    public function getDeviceType(): ?DeviceType
    {
        return $this->deviceType;
    }

    public function setDeviceType(?DeviceType $deviceType): self
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    public function getPriorityRating(): ?int
    {
        return $this->priority_rating;
    }

    public function setPriorityRating(?int $priority_rating): self
    {
        $this->priority_rating = $priority_rating;

        return $this;
    }

    public function getPluggedIn(): ?bool
    {
        return $this->plugged_in;
    }

    public function setPluggedIn(?bool $plugged_in): self
    {
        $this->plugged_in = $plugged_in;

        return $this;
    }
}
