<?php

use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config;

class VimeoEmbedTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected $commonInput = "[vimeo]http://vimeo.com/112233728[/vimeo]";

    protected function _before() 
    {
        Config::init('config');

        $this->lp = new Letterpress;
    }

    protected function _after() {}

    public function testFrameBasicEmbed()
    {
        $expected = '<iframe src="//player.vimeo.com/video/112233728" width="1280" height="720" '
                  . 'frameborder="0" title="Wetness" webkitallowfullscreen mozallowfullscreen '
                  . 'allowfullscreen></iframe>';

        $output = $this->lp->press($this->commonInput, ['letterpress.media.enableResponsiveIFrames' => false]);

        $this->assertEquals($expected, $output);
    }

    public function testFrameResponsiveEmbed()
    {
        $expected = '<div class="iframe img-responsive"><img class="ratio" src="//placehold.it/16x9&amp;text=+"'
                  . ' width="16" height="9"><iframe src="//player.vimeo.com/video/112233728" frameborder="0" '
                  . 'title="Wetness" webkitallowfullscreen mozallowfullscreen allowfullscreen data-width="1280" '
                  . 'data-height="720"></iframe></div>';
        
        $output = $this->lp->press($this->commonInput);
        $this->assertEquals($expected, $output);
    }

    public function testFrameEmbedInParagraph()
    {
        $input = "This is a dummy paragraph text. And you should totally look "
               . "at this video. [vimeo]http://vimeo.com/112233728[/vimeo] So cool, right?";
        $expected = "<p>This is a dummy para&shy;graph text. And you should totally look "
                  . "at this video.  So cool, right?</p>"
                  . '<div class="iframe img-responsive"><img class="ratio" src="//placehold.it/16x9&amp;text=+"'
                  . ' width="16" height="9"><iframe src="//player.vimeo.com/video/112233728" frameborder="0" '
                  . 'title="Wetness" webkitallowfullscreen mozallowfullscreen allowfullscreen data-width="1280" '
                  . 'data-height="720"></iframe></div>';

        $output = $this->lp->press($input);
        $this->assertEquals($expected, $output);
    }

    public function testTextEmbed()
    {
      $expected = '<div><h1>Wetness</h1><p>The only thing you hear going down the trail is your bike, you&rsquo;re '
                . 'tuned into every sound it makes. One of the most satis&shy;fy&shy;ing things is a tire '
                . 'patter&shy;ing down the trail, squash&shy;ing muck, slid&shy;ing over roots and smash&shy;ing '
                . 'puddles. Unfor&shy;tu&shy;nately this lush, raw sound is often lost in most videos, over&shy;taken '
                . 'by the music. Curtis Robin&shy;son takes advant&shy;age of winter condi&shy;tions and makes some '
                . "noise with his Stumpjumper FSR EVO.\n\nwww.special&shy;ized.com/stumpjumper-fsr\nwww.iamspe"
                . '&shy;cial&shy;ized.com<a href="http://vimeo.com/112233728">http://vimeo.com/112233728</a></p></div>';

      $output = $this->lp->press($this->commonInput, ['letterpress.media.videoEmbedMode' => 'text']);
      $this->assertEquals($expected, $output);
    }

    public function testLinkEmbed()
    {
      $expected = '<a href="http://vimeo.com/112233728">http://vimeo.com/112233728</a>';

      $output = $this->lp->press($this->commonInput, ['letterpress.media.videoEmbedMode' => 'link']);
      $this->assertEquals($expected, $output); 
    }
}
