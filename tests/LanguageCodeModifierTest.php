<?php

use EFrane\Letterpress\Markup\LanguageCodeModifier;

class LanguageCodeModifierTest extends MarkupModifierTest
{
    /**
     * @dataProvider provider
     */
    public function testLanguageCodeModifier($actual, $expected)
    {
        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function provider()
    {
        return [
            ['', ''],
            ['<div>Content</div>', '<div lang="en">Content</div>'],
            ['<p>Just some text content.</p>', '<p lang="en">Just some text content.</p>'],
            [
                '<div>Container 1</div><div><h2>Headline</h2>Container 2</div>',
                '<div lang="en">Container 1</div><div lang="en"><h2 lang="en">Headline</h2>Container 2</div>',
            ],
        ];
    }

    protected function setUnitUnderTest()
    {
        $this->processor->setModifiers([new LanguageCodeModifier()]);
    }
}
