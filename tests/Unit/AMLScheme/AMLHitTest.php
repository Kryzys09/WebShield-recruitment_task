<?php

namespace Tests\Unit\AMLScheme;

use App\Entity\AMLScheme\AMLCheck;
use App\Entity\AMLScheme\AMLHit;
use App\Entity\AMLScheme\AMLMonitor;
use App\Entity\Owner;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AMLHit::class)]
class AMLHitTest extends TestCase {
    function testHitAssignement(): AMLHit {
        $owner = new Owner(0, 'Doe');
        $owner->setAddress(
            'Poland',
            '00000',
            'Warsaw',
            'Default st.',
            '0'
        );
        $monitor = new AMLMonitor(0);
        $check = new AMLCheck(0, $monitor);
        $hit = new AMLHit(0, $owner, $check, '');

        $this->assertContains($hit, $owner->getCurrentAMLHits());

        return $hit;
    }
}