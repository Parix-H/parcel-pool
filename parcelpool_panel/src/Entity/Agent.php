<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgentRepository")
 */
class Agent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $agentUsername;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgentUsername(): ?string
    {
        return $this->agentUsername;
    }

    public function setAgentUsername(string $agentUsername): self
    {
        $this->agentUsername = $agentUsername;

        return $this;
    }
}
