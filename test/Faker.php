<?php

declare(strict_types=1);

namespace Test;

use Faker\Generator;

/**
 * @property-read string $entityName
 * @property-read \DateTimeImmutable $dateTimeImmutable
 * @mixin Generator
 */
class Faker
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function entityName():string
    {
        return sprintf('App\\Entity\\%s', $this->generator->firstName);
    }

    public function dateTimeImmutable():string
    {
        return new \DateTimeImmutable($this->generator->date);
    }

    public function changeSet():array
    {
        $numberFields =1;
        return [
            'was' => [
                ''
            ],
            'now' => [
                ''
            ]
        ];
    }

    public function __get(string $name)
    {
        return $this->$name();
    }

    public function __call(string $name, $arguments)
    {
        return $this->generator->$name(...$arguments);
    }
}
