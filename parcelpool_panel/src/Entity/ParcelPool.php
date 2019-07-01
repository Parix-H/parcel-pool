<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParcelPoolRepository")
 */
class ParcelPool
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\sourceCity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sourceCity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\destinationCity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destinationCity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfParcels;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\agent")
     */
    private $agentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceCity(): ?sourceCity
    {
        return $this->sourceCity;
    }

    public function setSourceCity(?sourceCity $sourceCity): self
    {
        $this->sourceCity = $sourceCity;

        return $this;
    }

    public function getDestinationCity(): ?destinationCity
    {
        return $this->destinationCity;
    }

    public function setDestinationCity(?destinationCity $destinationCity): self
    {
        $this->destinationCity = $destinationCity;

        return $this;
    }

    public function getNumberOfParcels(): ?int
    {
        return $this->numberOfParcels;
    }

    public function setNumberOfParcels(?int $numberOfParcels): self
    {
        $this->numberOfParcels = $numberOfParcels;

        return $this;
    }

    public function getAgentId(): ?agent
    {
        return $this->agentId;
    }

    public function setAgentId(?agent $agentId): self
    {
        $this->agentId = $agentId;

        return $this;
    }
}
