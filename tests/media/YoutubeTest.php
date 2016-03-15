<?php


use EFrane\Letterpress\Markup\RichMedia\LookupInterface;
use EFrane\Letterpress\Markup\RichMedia\Repository;
use EFrane\Letterpress\Markup\RichMedia\Video\YouTube;

class YoutubeTest extends MarkupModifierTest
{
    public function setUnitUnderTest()
    {
        $mock = $this->getMockBuilder(LookupInterface::class)
            ->setMethods(['getFrameSource', 'getUrl', 'getAdapter'])
            ->getMock();

        $mock->expects($this->once())->method('getFrameSource')->willReturn('<iframe width="459" height="344" src="https://www.youtube.com/embed/4gZ5rsAHMl4?feature=oembed" frameborder="0" allowfullscreen></iframe>');

        $repo = new Repository([
            'https://youtu.be/4gZ5rsAHMl4' => $mock,
        ]);

        $youTubeModifier = new YouTube();
        $youTubeModifier->setRepository($repo);

        $this->processor->setModifiers([$youTubeModifier]);
    }

    public function testYoutube()
    {
        $actual = '<a href="https://youtu.be/4gZ5rsAHMl4">How to make a video</a>';
        $expected = '<iframe width="459" height="344" src="https://www.youtube.com/embed/4gZ5rsAHMl4?feature=oembed" frameborder="0" allowfullscreen></iframe>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }
}
