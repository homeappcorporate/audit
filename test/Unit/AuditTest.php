<?php

declare(strict_types=1);

namespace Test\Unit;

use Homeapp\Audit\Audit;
use PHPUnit\Framework\TestCase;

/**
 * @covers Audit
 */
final class AuditTest extends TestCase
{
    private Audit $audit;

    protected function setUp() : void
    {
        parent::setUp();
        $this->audit = new Audit();
    }

    public function testVerifyClassIsWorkingAsExpected(): void
    {
        self::assertSame('ok', $this->audit->save());
    }
}
