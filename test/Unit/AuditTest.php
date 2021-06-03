<?php

declare(strict_types=1);

namespace Test\Unit;

use Faker\Factory;
use Faker\Generator;
use Homeapp\AuditBundle\ActivityData;
use Homeapp\AuditBundle\Audit;
use Homeapp\AuditBundle\StorageInterface;
use PHPUnit\Framework\TestCase;
use php_user_filter;

/**
 * @covers Audit
 */
final class AuditTest extends TestCase
{
    private Audit $audit;
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
                [
                    new ActivityData(
                        $faker->entityName,
                        $faker->uuid,
                        $faker->randomNumber(),
                        $faker->dateTimeImmutable,
                        $faker->ipv4,
                        $faker->changeSet
                    )
                ]
            ]
        ];
    }

    /**
     * @dataProvider getActivity
     */
    public function testSaveActivity(array $data): void
    {
        foreach ($data as $d) {
            $this->audit->save($d);
        }
    }
}
