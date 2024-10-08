<?php

namespace Tests\Unit\Validate;

use App\Validate\NameValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(NameValidator::class)]
final class NameValidatorTest extends TestCase {
    #[DataProvider('validPersonNames')]
    function testValidPersonName(string $name): void {
        $this->assertEmpty(NameValidator::validateName($name));
    }

    #[DataProvider('invalidPersonNames')]
    function testInvalidPersonName(string $name): void {
        $this->assertNotEmpty(NameValidator::validateName($name));
    }

    static function validPersonNames(): array {
        return [
            ['John'],
            ['Brzęczyszczykiewicz'],
            ['Özil'],
            ['Queen Elizabeth the II'],
            ['Luiz Nazario de Lima'],
            ['Fábio Coentrão'],
            ['Qi'],
            ['Przerwa-Tetmajer'],
            ["O'Connell"]
        ];
    }

    static function invalidPersonNames(): array {
        return [
            ['DROP DATABASE;'],
            ['SELECT * FROM secret_data'],
            ['Kubica 16'],
            ['不不'],
            ['لالا'],
            ['U'],
            ['aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa']
        ];
    }
}