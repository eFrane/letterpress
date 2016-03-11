<?php


use EFrane\Letterpress\Markup\LinkModifier;

class LinkModifierTest extends MarkupModifierTest
{
    protected function setUnitUnderTest()
    {
        $this->processor->resetModifiers();
    }

    /**
     * @dataProvider linkTestData
     */
    public function testLinkModifier($actual, callable $replacer, $expected)
    {
        $this->processor->setModifiers([new LinkModifier($replacer)]);
        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function linkTestData()
    {
        return [
            [
                '<div><a href="https://youtu.be"></a><a href="https://google.com"></a><p>Other content</p></div>',
                function ($url) {
                    if (str_contains($url, 'youtu.be')) {
                        return "This could be a video.";
                    } else return null;
                },
                '<div>This could be a video.<a href="https://google.com"></a><p>Other content</p></div>',
            ],
            [
                '<div><a href="https://youtu.be"></a><a href="https://google.com"></a><p>Other content</p></div>',
                function ($url, \DOMDocument $doc) {
                    if (str_contains($url, 'youtu.be')) {
                        $div = $doc->createElement('div', $url);
                        return $div;
                    } else return null;
                },
                '<div><div>https://youtu.be</div><a href="https://google.com"></a><p>Other content</p></div>',
            ]
        ];
    }
}
