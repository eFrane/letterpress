<?php


use EFrane\Letterpress\Markup\BlockQuoteModifier;

class BlockQuoteModifierTest extends MarkupModifierTest
{
    protected function setUnitUnderTest()
    {
        $this->processor->setModifiers([new BlockQuoteModifier()]);
    }

    /**
     * @dataProvider blockQuoteData
     */
    public function testBlockQuoteModifier($actual, $expected)
    {
        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function blockQuoteData()
    {
        return [
            // sanity checking that the modifier does not modify things it's not supposed to
            ['<p>Some non quoted content</p>', '<p>Some non quoted content</p>'],

            [
                '<blockquote>I think, therefore I am!</blockquote>',
                '<figure><blockquote>I think, therefore I am!</blockquote></figure>',
            ],

            [
                '<blockquote>I think, therefore I am!<ul><li>Rene Descartes</li></ul></blockquote>',
                '<figure><blockquote>I think, therefore I am!</blockquote><figcaption>Rene Descartes</figcaption></figure>',
            ],

            [
                '<blockquote>I think, therefore I am!</blockquote><ul><li>Rene Descartes</li></ul>',
                '<figure><blockquote>I think, therefore I am!</blockquote><figcaption>Rene Descartes</figcaption></figure>',
            ],
        ];
    }
}
