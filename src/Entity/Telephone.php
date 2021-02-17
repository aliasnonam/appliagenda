<?php

namespace App\Entity;

use App\Repository\TelephoneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TelephoneRepository::class)
 */
class Telephone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $numPhone;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="telephone")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumPhone(): ?string
    {
        return $this->numPhone;
    }

    public function setNumPhone(string $numPhone): self
    {
        $this->numPhone = $numPhone;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
