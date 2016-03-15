<?php


use EFrane\Letterpress\Markup\RichMedia\LookupInterface;
use EFrane\Letterpress\Markup\RichMedia\Repository;
use EFrane\Letterpress\Markup\RichMedia\Video\YouTube;

class YoutubeTest extends MediaTest
{
    public function setUnitUnderTest()
    {
        $youTubeModifier = new YouTube();
        $youTubeModifier->setRepository($this->generateRepository(
            'https://youtu.be/4gZ5rsAHMl4',
            '<iframe width="459" height="344" src="https://www.youtube.com/embed/4gZ5rsAHMl4?feature=oembed" frameborder="0" allowfullscreen></iframe>'
        ));

        $this->processor->setModifiers([$youTubeModifier]);
    }

    public function testYoutube()
    {
        $actual = '<a href="https://youtu.be/4gZ5rsAHMl4">How to make a video</a>';
        $expected = '<iframe width="459" height="344" src="https://www.youtube.com/embed/4gZ5rsAHMl4?feature=oembed" frameborder="0" allowfullscreen></iframe>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }
}
