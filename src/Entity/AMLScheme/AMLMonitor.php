<?php

namespace App\Entity\AMLScheme;

use App\Entity\Company;
use App\Entity\Owner;

final class AMLMonitor {
    private const HIGH_RISK_COMPANY_BENCHMARK = 4;
    private const MEDIUM_RISK_COMPANY_BENCHMARK = 2;

    /** @var AMLCheck[] */
    private array $checks;

    /** @var Company[] */
    private array $highRiskCompanies, $mediumRiskCompanies, $lowRiskCompanies;

    function __construct(private readonly int $id) {
        $this->checks = [];
        $this->highRiskCompanies = [];
        $this->mediumRiskCompanies = [];
        $this->lowRiskCompanies = [];
    }

    /** @param Owner[] $owners */
    public function performAMLMonitoring(array $owners): void {
        $companies = [];
        foreach ($owners as $owner) {
            $owner->clearCurrentAMLHits();
            foreach ($owner->getCompanies() as $company) {
                $companies[$company->getId()] = $company;
            }
        }

        $this->runChecksOnOwners($owners);
        $this->flagCompanies($companies);
    }

    /** @param Owner[] owners */
    function runChecksOnOwners(array $owners): void {
        echo "\nRunning checks.";
        foreach ($this->getChecks() as $check) {
            echo ".";
            foreach ($owners as $owner) {
                $check->runFullCheck($owner);
            }
            \sleep(0.75);
        }

        echo "\nChecks finished.\n";
    }

    /** @param Company[] $companies */
    function flagCompanies(array $companies): void {
        foreach ($companies as $company) {
            $hits = 0;
            foreach ($company->getOwners() as $owner) {
                $hits += \count($owner->getCurrentAMLHits());
            }
            if ($hits > self::HIGH_RISK_COMPANY_BENCHMARK)
                $this->addHighRiskCompany($company);
            else if ($hits > self::MEDIUM_RISK_COMPANY_BENCHMARK)
                $this->addMediumRiskCompany($company);
            else
                $this->addLowRiskCompany($company);
        }
    }

    public function getId(): int {
        return $this->id;
    }

    /** @return AMLCheck[] */
    public function getChecks(): array {
        return $this->checks;
    }

    public function addCheck(AMLCheck $check): self {
        if (!\in_array($check, $this->getChecks()))
            $this->checks[] = $check;

        $check->setMonitor($this);

        return $this;
    }

    /** @return Company[] */
    public function getHighRiskCompanies(): array {
        return $this->highRiskCompanies;
    }

    public function addHighRiskCompany(Company $company): self {
        $this->highRiskCompanies[] = $company;

        return $this;
    }

    /** @return Company[] */
    public function getMediumRiskCompanies(): array {
        return $this->mediumRiskCompanies;
    }

    public function addMediumRiskCompany(Company $company): self {
        $this->mediumRiskCompanies[] = $company;

        return $this;
    }

    /** @return Company[] */
    public function getLowRiskCompanies(): array {
        return $this->lowRiskCompanies;
    }

    public function addLowRiskCompany(Company $company): self {
        $this->lowRiskCompanies[] = $company;

        return $this;
    }
}