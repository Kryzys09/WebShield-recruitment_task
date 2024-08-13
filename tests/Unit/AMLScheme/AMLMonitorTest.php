<?php

namespace Tests\Unit\AMLScheme;

use App\Entity\AMLScheme\AMLCheck;
use App\Entity\AMLScheme\AMLHit;
use App\Entity\AMLScheme\AMLMonitor;
use App\Entity\Company;
use App\Entity\Owner;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DependsExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(AMLMonitor::class)]
class AMLMonitorTest extends TestCase {
    #[DependsExternal(AMLHitTest::class, 'testHitAssignement')]
    function testOwnerHitClearance(AMLHit $hit): void {
        $owner = $hit->getOwner();
        $monitor = $hit->getCheck()->getMonitor();

        $monitor->performAMLMonitoring([$owner]);
        $this->assertNotContains($hit, $owner->getCurrentAMLHits());
    }

    function testExtensiveMonitoring(): void {
        [$monitor, $owners] = $this->monitoringTestSetup();

        $monitor->performAMLMonitoring($owners);
        echo "\n\n(company_id - num_of_owner_hits)\n";
        echo "High risk companies: ";
        foreach ($monitor->getHighRiskCompanies() as $id => $hits) {
            echo "\n$id - $hits";
        }
        echo "\n";
        echo "Medium risk companies: ";
        foreach ($monitor->getMediumRiskCompanies() as $id => $hits) {
            echo "\n$id - $hits";
        }
        echo "\n";
        echo "Low risk companies: ";
        foreach ($monitor->getLowRiskCompanies() as $id => $hits) {
            echo "\n$id - $hits";
        }
        echo "\n";
    }

    /** @return array(Owner[], Company[]) */
    private function companyOwnersSetup(): array {
        $companies = [];
        for ($i = 1; $i <= 23; $i++) {
            $companies[] = new Company($i, $i . ' Ltd.');
        }
        $owners = [];
        $countries = ['Austria', 'Belgium', 'Spain', 'United Kingdom', 'Poland', 'Germany', 'Portugal', 'Croatia', 'Italy', 'France', 'Slovakia'];
        for ($i = 1; $i <= 50; $i++) {
            $owner = new Owner($i, 'Mr. XYZ');
            $owner->setAddress($countries[$i % \count($countries)], '00000', 'a', 'a', '0');
            $j = $i % 4;
            while ($j >= 0) {
                $owner->addCompany($companies[($i * ($j + 1)) % \count($companies)]);
                $j--;
            }
            
            $owners[] = $owner;
        }

        return [$owners, $companies];
    }

    /** @return array(AMLMonitor, Owner[], Company[]) */
    private function monitoringTestSetup(): array {
        $monitor = new AMLMonitor(0);
        [$owners, $companies] = $this->companyOwnersSetup();

        new AMLCheck(0, $monitor, AMLCheck::TYPE_1);
        new AMLCheck(1, $monitor, AMLCheck::TYPE_2);
        new AMLCheck(2, $monitor, AMLCheck::TYPE_3);

        return [$monitor, $owners, $companies];
    }
}