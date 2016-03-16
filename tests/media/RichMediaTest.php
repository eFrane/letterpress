<?php

use EFrane\Letterpress\Markup\RichMedia\LookupInterface;
use EFrane\Letterpress\Markup\RichMedia\Repository;
use EFrane\Letterpress\Markup\RichMedia\Video\YouTube;

/**
 * This test case uses a very simplified mock of the
 * YouTube test to check if all rich media integrations work
 * as expected. The actual replacement checks for the different
 * link patterns are done in YoutubeTest, as with all other
 * integrations.
 */
class RichMediaTest extends MarkupModifierTest
{
    protected $link = 'https://youtu.be/4gZ5rsAHMl4';

    public function testDoesNotApplyOnDummyText()
    {
        $expected = $actual = 'I am just a short paragraph.';

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function testDoesNotApplyOnDummyParagraph()
    {
        $expected = $actual = '<p>I am just a short paragraph.</p>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function testDoesNotApplyOnOtherLink() {
        $expected = $actual = '<a href="http://i-lead.nowhere/">I lead nowhere</a>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function testDoesApplyOnMatchingLinkStandalone() {
        $actual = '<a href="' . $this->link . '">Video</a>';
        $expected = 'frameSource';

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function testDoesApplyOnMatchingLinkInContext()
    {
        $actual = '<p>I am a text paragraph <a href="'. $this->link . '">linking to a video</a>.</p>';
        $expected = '<p>I am a text paragraph frameSource.</p>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    protected function setUnitUnderTest()
    {
        $modifier = new YouTube();

        $lookup = $this->getMockBuilder(LookupInterface::class)
            ->setMethods(['getFrameSource', 'getUrl', 'getAdapter'])
            ->getMock();

        $lookup->method('getFrameSource')->willReturn('frameSource');
        $lookup->method('getUrl')->willReturn($this->link);

        $modifier->setRepository(new Repository([
            $this->link => $lookup
        ]));

        $this->processor->setModifiers([$modifier]);
    }
}
