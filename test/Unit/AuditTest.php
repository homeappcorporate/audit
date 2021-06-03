<?php

declare(strict_types=1);

namespace Test\Unit;

use Homeapp\AuditBundle\HomeappAuditBundle;
use PHPUnit\Framework\TestCase;

/**
 * @covers HomeappAuditBundle
 */
final class AuditTest extends TestCase
{
    private HomeappAuditBundle $audit;

    protected function setUp(): void
    {
        parent::setUp();
        $this->audit = new HomeappAuditBundle();
    }

    public function testVerifyClassIsWorkingAsExpected(): void
    {
        self::assertSame('ok', $this->audit->save());
    }
}
