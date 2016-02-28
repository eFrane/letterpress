<?php

use EFrane\Letterpress\Microtypography\GermanNumbers;

class GermanNumbersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider numbersData
     */
    public function testGermanNumbers($actual, $expected)
    {
        $gn = new GermanNumbers('de_DE');

        $this->assertEquals($expected, $gn->fix($actual));
    }

    public function numbersData()
    {
        return [
            ['1', '1'],
            ['1234', '1 234'],
            ['123.45', '123,45'],
            ['1234.56', '1 234,56'],
        ];
    }
}
