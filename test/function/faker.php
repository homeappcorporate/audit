<?php

declare(strict_types=1);

use Faker\Factory;
use Test\Infra\Faker;

function faker(): Faker
{
    return new Faker(Factory::create());
}
