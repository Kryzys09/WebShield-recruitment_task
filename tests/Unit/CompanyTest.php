<?php

namespace Tests\Unit;

use App\Entity\Company;
use App\Entity\Owner;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;

#[CoversClass(Company::class)]
final class CompanyTest extends TestCase {
    /**
     * @var Company
     */
    private $company;

    function setUp(): void {
        $this->company = new Company('1', 'abcd Ltd.');
    }

    function testIncorrectOwnersSupplied(): void {
        // just an anonymous dummy object similar to an Owner object
        $dummy = new class(0, 'aaa', 'bbb') {function __construct(private int $id, private string $lastName, private string $firstName){}};
        $this->expectException(\TypeError::class);
        new Company(1, 'aaa', [$dummy]);
    }

    function testOwnerAddition(): Owner {
        $owner = new Owner(0, 'Doe', 'John');

        $this->company->addOwner($owner);
        $this->assertContains($owner, $this->company->getOwners(), "Owner was not assigned to the company!");
        $this->assertContains($this->company, $owner->getCompanies(), "Company wasn't assigned to the added Owner!");

        return $owner;
    }

    #[Depends('testOwnerAddition')]
    function testOwnerRemoval(Owner $owner): void {
        $this->company->removeOwner($owner);
        $this->assertNotContains($owner, $this->company->getOwners(), "Owner was not removed from the company!");
        $this->assertNotContains($this->company, $owner->getCompanies(), "Company wasn't unassigned from the removed Owner!");
    }
}