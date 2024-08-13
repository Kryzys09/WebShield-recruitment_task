<?php

namespace App\Entity;

use App\Exception\InvalidPropertyException;
use App\Validate\NameValidator;
use App\ValueObject\Address;

final class Company {
    private string $name;
    private ?Address $address;

    /** @var Owner[] */
    private array $owners;

    /** @throws InvalidPropertyException */
    function __construct(private readonly int $id, string $name) {
        $this->owners = [];
        $this->setName($name);
    }

    function __toString(): string {
        return $this->getId() . '_' . $this->getName();
    }

    public function getAddress(): ?Address {
        return $this->address;
    }

    /** @throws InvalidPropertyException */
    public function setAddress(string $country, string $zipCode, string $city, string $street, string $streetNr, ?string $apartmentNr = null): self {
        $this->address = new Address($country, $zipCode, $city, $street, $streetNr, $apartmentNr);

        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $violations = NameValidator::validateCompanyName($name);
        if ($violations)
            throw new InvalidPropertyException($violations[0]);
        
        $this->name = $name;

        return $this;
    }

    /** @return Owner[] */
    public function getOwners(): array {
        return $this->owners;
    }

    public function addOwner(Owner $owner): self {
        if (!\in_array($owner, $this->owners)) {
            $this->owners[] = $owner;
            $owner->addCompany($this);
        }

        return $this;
    }

    public function removeOwner(Owner $owner): self {
        if (($internalID = \array_search($owner, $this->owners)) !== false) {
            unset($this->owners[$internalID]);
            $owner->removeCompany($this);
        }

        return $this;
    }
}