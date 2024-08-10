<?php

namespace App\Entity\AMLScheme;

use App\Entity\Owner;
use App\Exception\InvalidPropertyException;

final class AMLCheck {
    public const TYPE_1 = 1;
    public const TYPE_2 = 2;
    public const TYPE_3 = 3;

    /** @var AMLHit[] */
    private array $amlHits;

    private AMLMonitor $monitor;

    function __construct(
        private readonly int $id,
        AMLMonitor $monitor,
        private readonly int $type = self::TYPE_1,
        private \DateTime $date = new \DateTime()
    ) {
        $this->amlHits = [];
        $this->setMonitor($monitor);
    }

    function runFullCheck(Owner $owner): void {
        $info = $this->customerDueDiligence($owner);
        $riskLevel = $this->riskAssessment($owner, $info);

        if ($riskLevel >= 2) {
            if ($details = $this->enhancedDueDiligence($owner)) {
                new AMLHit($owner, $this, $details);
            }
        }
    }

    /** Simple mock method - in theory this method returns some info about given owner */
    protected function customerDueDiligence(Owner $owner): string {
        $info = "the info";

        return $info;
    }

    /** Simple mock method - in theory this method returns the level of risk (0-3) associated with given owner */
    protected function riskAssessment(Owner $owner, string $info): int {
        // some magic to consistently mock which owners get flagged
        $risk = $owner->getId();

        switch ($this->getType()) {
            case self::TYPE_1:
                if ($owner->getAddress()?->getCountry() === 'UK' || $owner->getAddress()?->getCountry() === 'Spain')
                    $risk++;
                if ($owner->getAddress()?->getCountry() === 'Portugal')
                    $risk += 2;
                break;
            case self::TYPE_2:
                if (\count($owner->getCompanies()) > 1)
                    $risk = 3;
                break;
            case self::TYPE_3:
                break;
        }
        $risk %= 4;

        return $risk;
    }

    /** Simple mock method - in theory this method returns details for owners to be flagged to hit, and empty string for other owners */
    protected function enhancedDueDiligence(Owner $owner): string {
        if (\ord($owner->getLastName()[0]) % 2)
            return "This person is 100% money laundering!!!";

        return "";
    }

    public function getId(): int {
        return $this->id;
    }

    public function getAMLHits(): array {
        return $this->amlHits;
    }

    public function addAMLHit(AMLHit $amlHit): self {
        if ($this !== $amlHit->getCheck())
            throw new InvalidPropertyException('Trying to assign to an AMLCheck an AMLHit of another AMLCheck!');

        $this->amlHits[] = $amlHit;

        return $this;
    }
    
    public function getMonitor(): AMLMonitor {
        return $this->monitor;
    }
    
    public function setMonitor(AMLMonitor $monitor): self {
        $this->monitor = $monitor;
        if (!\in_array($this, $monitor->getChecks()))
            $monitor->addCheck($this);

        return $this;
    }
    
    public function getDate(): \DateTime {
        return $this->date;
    }
    
    public function setDate(\DateTime $date): self {
        $this->date = $date;

        return $this;
    }

    public function getType(): int{
        return $this->type;
    }
}