<?php

declare(strict_types=1);

namespace Test\Infra;

use Faker\Generator;

/**
 * @method string firstName($gender = null)
 * @method string date($format = 'Y-m-d', $max = 'now')
 * @method string e164PhoneNumber()
 * @method string uuid()
 * @method string ipv4()
 * @mixin Generator
 */
class Faker
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

//    public function entityName():string
//    {
//        return sprintf('App\\Entity\\%s', $this->generator->name());
//    }

//    public function dateTimeImmutable():\DateTimeImmutable
//    {
//        return new \DateTimeImmutable($this->generator->date());
//    }
//
//    /**
//     * @return string[]
//     */
//    public function changeSet():array
//    {
//        return [
//            'a',
//            'b',
//            'c',
//        ];
//    }

    /**
     * @psalm-suppress MissingParamType
     */
    public function __call(string $name, $arguments)
    {
        return $this->generator->$name(...$arguments);
    }
}
