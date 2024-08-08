<?php

namespace App\Entity;

use App\Validate\NameValidator;

final class Company {
    /**
     * @var Owner[]
     */
    private $owners;

    /**
     * @param Owner[] owners
     */
    function __construct(private int $id, private string $name, array $owners = []) {
        $this->owners = [];
        foreach ($owners as $owner) {
            $this->addOwner($owner);
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $violations = NameValidator::validateCompanyName($name);
        if (!$violations)
            $this->name = $name;

        return $this;
    }

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