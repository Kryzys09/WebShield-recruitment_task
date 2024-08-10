<?php

namespace App\Entity\AMLScheme;

use App\Entity\Owner;

final readonly class AMLHit
{
    private Owner $owner;
    private AMLCheck $check;

    function __construct(Owner $owner, AMLCheck $check, private string $details) {
        $this->setOwner($owner);
        $this->setCheck($check);
    }

    public function getOwner(): Owner {
        return $this->owner;
    }

    public function setOwner(Owner $owner): self {
        $this->owner = $owner;
        if (!\in_array($this, $owner->getCurrentAMLHits()))
            $owner->addAMLHit($this);

        return $this;
    }

    public function getCheck(): AMLCheck {
        return $this->check;
    }

    public function setCheck(AMLCheck $check): self {
        $this->check = $check;
        if (!\in_array($this, $check->getAMLHits()))
            $check->addAMLHit($this);

        return $this;
    }

    public function getDetails(): string {
        return $this->details;
    }
}