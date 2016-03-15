<?php


use EFrane\Letterpress\Markup\RichMedia\Video\YouTube;

class YoutubeTest extends MarkupModifierTest
{
    public function setUnitUnderTest()
    {
        $this->processor->setModifiers([new YouTube()]);
    }

    public function testYoutube() {
        $actual = '<a href="https://youtu.be/4gZ5rsAHMl4">How to make a video</a>';
        $expected = '';

        $this->assertEquals($expected, $this->processor->process($actual));
    }
}
