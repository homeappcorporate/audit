<?php

declare(strict_types=1);

use Faker\Factory;
use Test\Faker;

$faker = null;
function faker():Faker use (&$faker) {
    if (!$faker) {
        $faker = new Faker(Factory::create());
    }
    return $faker;
}
