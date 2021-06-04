<?php

declare(strict_types=1);

namespace Test\Unit;

use Homeapp\AuditBundle\ActivityData;
use Homeapp\AuditBundle\Audit;
use Homeapp\AuditBundle\StorageInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers Audit
 * @psalm-suppress UnusedClass
 */
final class AuditTest extends TestCase
{
    private Audit $audit;
    /** @var StorageInterface&MockObject  */
    private StorageInterface $storage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storage = $this->createMock(StorageInterface::class);
        $this->audit = new Audit($this->storage);
    }

    public function getActivity():array
    {
        $faker = faker();
        return [
            [
                new ActivityData(
                    $faker->entityName(),
                    $faker->uuid(),
                    $faker->randomNumber(),
                    $faker->ipv4(),
                    $faker->changeSet()
                )
            ]
        ];
    }

    /**
     * @dataProvider getActivity
     */
    public function testSaveActivity(ActivityData ...$data): void
    {
        $this->storage->expects(self::once())->method('send')->with(...$data);
        foreach ($data as $d) {
            $this->audit->hold($d);
        }
        $this->audit->sendDataToPersistenceStorage();
        self::assertEmpty((function (){
            /** @var Audit $this */
            return $this->data;
        })->call($this->audit));
    }
}
