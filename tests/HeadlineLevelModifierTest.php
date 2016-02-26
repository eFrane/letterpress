<?php

use EFrane\Letterpress\Markup\HeadlineLevelModifier;

class HeadlineLevelModifierTest extends \MarkupModifierTest
{
    /**
     * @dataProvider headlineData
     */
    public function testHeadlineLevelModifier($level, $actual, $expected)
    {
        $this->processor->setModifiers([new HeadlineLevelModifier($level)]);
        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function headlineData()
    {
        return [
            [1, '<h1>Headline</h1>', '<h1>Headline</h1>'],
            [1, '<h2>Headline</h2>', '<h2>Headline</h2>'],
            [1, '<h3>Headline</h3>', '<h3>Headline</h3>'],
            [1, '<h4>Headline</h4>', '<h4>Headline</h4>'],
            [1, '<h5>Headline</h5>', '<h5>Headline</h5>'],
            [1, '<h6>Headline</h6>', '<h6>Headline</h6>'],

            [2, '<h1>Headline</h1>', '<h2>Headline</h2>'],
            [2, '<h2>Headline</h2>', '<h2>Headline</h2>'],
            [2, '<h3>Headline</h3>', '<h3>Headline</h3>'],
            [2, '<h4>Headline</h4>', '<h4>Headline</h4>'],
            [2, '<h5>Headline</h5>', '<h5>Headline</h5>'],
            [2, '<h6>Headline</h6>', '<h6>Headline</h6>'],

            [3, '<h1>Headline</h1>', '<h3>Headline</h3>'],
            [3, '<h2>Headline</h2>', '<h3>Headline</h3>'],
            [3, '<h3>Headline</h3>', '<h3>Headline</h3>'],
            [3, '<h4>Headline</h4>', '<h4>Headline</h4>'],
            [3, '<h5>Headline</h5>', '<h5>Headline</h5>'],
            [3, '<h6>Headline</h6>', '<h6>Headline</h6>'],

            [4, '<h1>Headline</h1>', '<h4>Headline</h4>'],
            [4, '<h2>Headline</h2>', '<h4>Headline</h4>'],
            [4, '<h3>Headline</h3>', '<h4>Headline</h4>'],
            [4, '<h4>Headline</h4>', '<h4>Headline</h4>'],
            [4, '<h5>Headline</h5>', '<h5>Headline</h5>'],
            [4, '<h6>Headline</h6>', '<h6>Headline</h6>'],

            [5, '<h1>Headline</h1>', '<h5>Headline</h5>'],
            [5, '<h2>Headline</h2>', '<h5>Headline</h5>'],
            [5, '<h3>Headline</h3>', '<h5>Headline</h5>'],
            [5, '<h4>Headline</h4>', '<h5>Headline</h5>'],
            [5, '<h5>Headline</h5>', '<h5>Headline</h5>'],
            [5, '<h6>Headline</h6>', '<h6>Headline</h6>'],

            [6, '<h1>Headline</h1>', '<h6>Headline</h6>'],
            [6, '<h2>Headline</h2>', '<h6>Headline</h6>'],
            [6, '<h3>Headline</h3>', '<h6>Headline</h6>'],
            [6, '<h4>Headline</h4>', '<h6>Headline</h6>'],
            [6, '<h5>Headline</h5>', '<h6>Headline</h6>'],
            [6, '<h6>Headline</h6>', '<h6>Headline</h6>'],
        ];
    }

    protected function setUnitUnderTest()
    {
        $this->processor->resetModifiers();
    }
}
