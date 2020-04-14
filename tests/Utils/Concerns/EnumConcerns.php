<?php
namespace Tests\Utils\Concerns;

use MyCLabs\Enum\Enum;

trait EnumConcerns
{
    public function assertSameEnum(Enum $expected, Enum $value)
    {
        $this->assertTrue($expected->equals($value), "$expected does not equal $value");
    }
}
