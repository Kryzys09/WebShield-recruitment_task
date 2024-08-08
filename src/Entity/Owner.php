<?php

namespace App\Entity;

use App\Validate\NameValidator;

final class Owner {
    /**
     * @var Company[]
     */
    private $companies;

    /**
     * @param Company[] companies
     */
    function __construct(private int $id, private string $lastName, private ?string $firstName = null, array $companies = []) {
        $this->companies = [];
        foreach ($companies as $company) {
            $this->addCompany($company);
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self {
        $violations = NameValidator::validateName($lastName);
        if (!$violations)
            $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self {
        $violations = NameValidator::validateName($firstName);
        if (!$violations)
            $this->firstName = $firstName;

        return $this;
    }

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
}