<?php

use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config;

class YouTubeEmbedTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected $commonInput = "[youtube]https://www.youtube.com/watch?v=UF8uR6Z6KLc[/youtube]";

    protected function _before() 
    {
        Config::init();

        $this->lp = new Letterpress;
    }

    protected function _after() {}

    public function testFrameBasicEmbed()
    {
        $expected = "<iframe width=\"459\" height=\"344\" "
                  . "src=\"http://www.youtube.com/embed/UF8uR6Z6KLc?feature=oembed\" "
                  . "frameborder=\"0\" allowfullscreen></iframe>";

        $output = $this->lp->press($this->commonInput, ['letterpress.media.enableResponsiveIFrames' => false]);

        $this->assertEquals($expected, $output);
    }

    public function testFrameResponsiveEmbed()
    {
        $expected = "<div class=\"iframe img-responsive\">"
                  . "<img class=\"ratio\" src=\"//placehold.it/16x9&amp;text=+\" width=\"16\" height=\"9\">"
                  . "<iframe src=\"http://www.youtube.com/embed/UF8uR6Z6KLc?feature=oembed\""
                  . " frameborder=\"0\" allowfullscreen data-width=\"459\" data-height=\"344\">"
                  . "</iframe></div>";
        
        $output = $this->lp->press($this->commonInput);
        $this->assertEquals($expected, $output);
    }

    public function testFrameEmbedInParagraph()
    {
        $input = "This is a dummy paragraph text. And you should totally look "
               . "at this video. [youtube]https://www.youtube.com/watch?v=UF8uR6Z6KLc[/youtube] So cool, right?";
        $expected = "<p>This is a dummy para&shy;graph text. And you should totally look "
                  . "at this video.  So cool, right?</p><div class=\"iframe img-responsive\">"
                  . "<img class=\"ratio\" src=\"//placehold.it/16x9&amp;text=+\" width=\"16\" "
                  . "height=\"9\"><iframe src=\"http://www.youtube.com/embed/UF8uR6Z6KLc?feature=oembed\" "
                  . "frameborder=\"0\" allowfullscreen data-width=\"459\" data-height=\"344\"></iframe></div>";

        $output = $this->lp->press($input);
        $this->assertEquals($expected, $output);
    }

    public function testTextEmbed()
    {
      $expected = '<div><h1>Steve Jobs&rsquo; 2005 Stan&shy;ford Commence&shy;ment Address</h1>'
                . '<p>Draw&shy;ing from some of the most pivotal points in his life, Steve Jobs, '
                . 'chief exec&shy;ut&shy;ive officer and co-founder of Apple Computer and of Pixar '
                . 'Anim&shy;a&shy;tion Studi&shy;os&hellip;<a href="http://www.youtube.com/watch?v=UF8uR6Z6KLc">'
                . 'http://www.youtube.com/watch?v=UF8uR6Z6KLc</a></p></div>';

      $output = $this->lp->press($this->commonInput, ['letterpress.media.videoEmbedMode' => 'text']);
      $this->assertEquals($expected, $output);
    }

    public function testLinkEmbed()
    {
      $expected = '<a href="http://www.youtube.com/watch?v=UF8uR6Z6KLc">http://www.youtube.com/watch?v=UF8uR6Z6KLc</a>';

      $output = $this->lp->press($this->commonInput, ['letterpress.media.videoEmbedMode' => 'link']);
      $this->assertEquals($expected, $output); 
    }
}
