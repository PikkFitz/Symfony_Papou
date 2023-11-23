<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer extends User
{
    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\NotBlank()]  // Car ne doit pas être vide (ni null)
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\NotBlank()]  // Car ne doit pas être vide (ni null)
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull()]
    private ?string $gender = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $birthdate = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?bool $agreeTerm = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull()]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Address::class, orphanRemoval: true)]
    private Collection $addresses;

    public function __construct()
    {
        parent::__construct(); // Appelle le construct de User
        $this->setRoles(['ROLE_USER', 'ROLE_CUSTOMER']);
        $this->addresses = new ArrayCollection();
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function isAgreeTerm(): ?bool
    {
        return $this->agreeTerm;
    }

    public function setAgreeTerm(bool $agreeTerm): self
    {
        $this->agreeTerm = $agreeTerm;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setCustomer($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCustomer() === $this) {
                $address->setCustomer(null);
            }
        }

        return $this;
    }
}
