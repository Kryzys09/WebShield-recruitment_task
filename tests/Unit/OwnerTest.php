<?php

namespace Tests\Unit;

use App\Entity\Company;
use App\Entity\Owner;
use App\Exception\InvalidPropertyException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;

#[CoversClass(Owner::class)]
final class OwnerTest extends TestCase {
    private Owner $owner;

    function setUp(): void {
        $this->owner = new Owner('1', 'Doe', 'John');
    }

    function testCreationFromIncorrectData(): void {
        $this->expectException(InvalidPropertyException::class);
        new Owner(1, 'AB123', 'John');
    }

    function testCompanyAddition(): Company {
        $company = new Company(0, 'abcd Ltd.');

        $this->owner->addCompany($company);
        $this->assertContains($company, $this->owner->getCompanies(), "Company was not assigned to the owner!");
        $this->assertContains($this->owner, $company->getOwners(), "Owner wasn't assigned to the added Company!");

        return $company;
    }

    #[Depends('testCompanyAddition')]
    function testCompanyRemoval(Company $company): void {
        $this->owner->removeCompany($company);
        $this->assertNotContains($company, $this->owner->getCompanies(), "Company was not removed from the owner!");
        $this->assertNotContains($this->owner, $company->getOwners(), "Owner wasn't unassigned from the removed Company!");
    }
}