<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DestinationCityRepository")
 */
class DestinationCity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="The city name cannot contain a number"
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "The city name must be at least {{ limit }} characters long",
     *      maxMessage = "The city name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank(message="An empty field is NOT accepted!")
     */
    private $destinationCity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinationCity(): ?string
    {
        return $this->destinationCity;
    }

    public function setDestinationCity(string $destinationCity): self
    {
        $this->destinationCity = $destinationCity;

        return $this;
    }
}
