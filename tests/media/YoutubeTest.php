<?php


use EFrane\Letterpress\Markup\RichMedia\Repository;
use EFrane\Letterpress\Markup\RichMedia\Video\YouTube;

class YoutubeTest extends MarkupModifierTest
{
    public function setUnitUnderTest()
    {
        // TODO: mock the repository with all required return methods
        // NOTE: as in: getFrameSource should return the required frame code, ...
        $repo = new Repository();

        $youTubeModifier = new YouTube();
        $youTubeModifier->setRepository($repo);

        $this->processor->setModifiers([$youTubeModifier]);
    }

    public function testYoutube() {
        $actual = '<a href="https://youtu.be/4gZ5rsAHMl4">How to make a video</a>';
        $expected = '<iframe width="459" height="344" src="https://www.youtube.com/embed/4gZ5rsAHMl4?feature=oembed" frameborder="0" allowfullscreen></iframe>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }
}
