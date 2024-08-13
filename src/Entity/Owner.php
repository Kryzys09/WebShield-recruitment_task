<?php

namespace App\Entity;

use App\Entity\AMLScheme\AMLHit;
use App\Exception\InvalidPropertyException;
use App\Validate\NameValidator;
use App\ValueObject\Address;

final class Owner {
    private string $lastName;
    private ?string $firstName;
    private ?Address $address;

    /** @var Company[] */
    private array $companies;

    /** @var AMLHit[] */
    private array $currentAMLHits;

    /** @throws InvalidPropertyException */
    function __construct(private readonly int $id, string $lastName, ?string $firstName = null) {
        $this->companies = [];
        $this->currentAMLHits = [];
        $this->setLastName($lastName);
        if ($firstName !== null) $this->setFirstName($firstName);
    }

    function __toString(): string {
        return $this->getId() . '_' . $this->getLastName();
    }

    public function getAddress(): ?Address {
        return $this->address;
    }

    /** @throws InvalidPropertyException */
    public function setAddress(
        string $country,
        string $zipCode,
        string $city,
        string $street,
        string $streetNr,
        ?string $apartmentNr = null
    ): self {
        $this->address = new Address($country, $zipCode, $city, $street, $streetNr, $apartmentNr);

        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self {
        $violations = NameValidator::validateName($lastName, alias: 'last name');
        if ($violations)
            throw new InvalidPropertyException($violations[0]);

        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self {
        $violations = NameValidator::validateName($firstName, alias: 'first name');
        if ($violations)
            throw new InvalidPropertyException($violations[0]);

        $this->firstName = $firstName;

        return $this;
    }

    /** @return Company[] */
    public function getCompanies(): array {
        return $this->companies;
    }

    public function addCompany(Company $company): self {
        if (!\in_array($company, $this->companies)) {
            $this->companies[] = $company;
            $company->addOwner($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self {
        if (($internalID = \array_search($company, $this->companies)) !== false) {
            unset($this->companies[$internalID]);
            $company->removeOwner($this);
        }

        return $this;
    }

    /** @return AMLHit[] */
    public function getCurrentAMLHits(): array {
        return $this->currentAMLHits;
    }

    public function addAMLHit(AMLHit $amlHit): self {
        if ($this !== $amlHit->getOwner())
            throw new InvalidPropertyException('Trying to assign to an owner an AMLHit of another owner!');

        $this->currentAMLHits[] = $amlHit;

        return $this;
    }

    public function clearCurrentAMLHits(): self {
        $this->currentAMLHits = [];

        return $this;
    }
}