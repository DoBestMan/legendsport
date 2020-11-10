<?php

namespace Unit\Http\Transformers\App;

use App\Betting\SportEvent\Sport;
use App\Http\Transformers\App\SportTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Http\Transformers\App\SportTransformer
 * @uses \App\Betting\SportEvent\Sport
 */
class SportTransformerTest extends TestCase
{
    public function testTransform()
    {
        $sport = new Sport('1', 'Laser tag', 'testdata');
        $expected = [
            'id' => '1',
            'name' => 'Laser tag',
            'provider' => 'testdata',
        ];

        $sut = new SportTransformer();
        $result = $sut->transform($sport);

        self::assertEquals($expected, $result);
    }
}
