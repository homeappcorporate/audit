<?php

declare(strict_types=1);

namespace Test\Infra\Stub;

use Homeapp\AuditBundle\ActorInfoFetcherInterface;
use Test\Infra\Faker;

class ActorInfoFetcher implements ActorInfoFetcherInterface
{
    private Faker $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function getId(): ?int
    {
        return $this->faker->numberBetween();
    }

    public function getIp(): ?string
    {
        return $this->faker->ipv4();
    }
}
