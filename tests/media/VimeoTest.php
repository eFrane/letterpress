<?php

use EFrane\Letterpress\Markup\RichMedia\Video\Vimeo;

class VimeoTest extends MediaTest
{
    public function testVimeo()
    {
        $actual = '<a href="https://vimeo.com/156015647">Emergence</a>';
        $expected = '<iframe src="https://player.vimeo.com/video/156015647" width="1920" height="1080" frameborder="0" title="Emergence" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

        $this->assertEquals($expected, $this->processor->process($actual));
    }

    protected function setUnitUnderTest()
    {
        $vimeoModifier = new Vimeo();
        $vimeoModifier->setRepository($this->generateRepository(
            'https://vimeo.com/156015647',
            '<iframe src="https://player.vimeo.com/video/156015647" width="1920" height="1080" frameborder="0" title="Emergence" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
        ));

        $this->processor->setModifiers([$vimeoModifier]);
    }
}
