<?php


use EFrane\Letterpress\Markup\TextModifier;

class TextModifierTest extends MarkupModifierTest
{
    protected function setUnitUnderTest()
    {
        $this->processor->resetModifiers();
    }

    /**
     * @dataProvider data
     */
    public function testTextModifier($pattern, $replacer, $actual, $expected)
    {
        $this->processor->setModifiers([new TextModifier($pattern, $replacer)]);

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function data()
    {
        return [
            [
                '/hello/',
                function ($input, $matches) { return "{$matches[0]} world"; },
                'hello',
                'hello world',
            ],

            [
                '/i am not here/',
                function ($input) { return $input; },
                'this is some random text',
                'this is some random text',
            ],
        ];
    }
}
