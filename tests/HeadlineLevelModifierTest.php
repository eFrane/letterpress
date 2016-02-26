<?php

class HeadlineLevelModifierTest extends MarkupModifierTest
{
    public function testHeadlineLevelModifier($level, $actual, $expected)
    {

    }

    public function headlineData()
    {
        return [
            [1, '<h3><h3>', '<h3></h3>'],
        ];
    }

    protected function setUnitUnderTest()
    {
        $this->processor->resetModifiers();
    }
}
