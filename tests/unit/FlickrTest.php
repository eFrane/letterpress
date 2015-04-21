<?php
use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config;

class Flickr extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected $baseInput = "[flickr]https://www.flickr.com/photos/b-erta/3823625739/[/flickr]";

    protected function _before() 
    {
        Config::init();

        $this->lp = new Letterpress;
    }

    protected function _after() {}

    public function testFlickrDefault()
    {
        $expected = '<a data-flickr-embed="true" href="https://www.flickr.com/photos/b-erta/3823625739/" title="Untitled by B erta, on Flickr"></a><script async src="//widgets.flickr.com/embedr/embedr.js" charset="utf-8"></script>';

        $output = $this->lp->press($this->baseInput);

        $this->assertEquals($expected, $output);
    }

    public function testMultiple()
    {
        $input = str_repeat($this->baseInput."\n", 2);
        $expected = '<a data-flickr-embed="true" href="https://www.flickr.com/photos/b-erta/3823625739/" title="Untitled by B erta, on Flickr"></a><script async src="//widgets.flickr.com/embedr/embedr.js" charset="utf-8"></script>';

        $output = $this->lp->press($input);

        $this->assertEquals($expected, $output);
    }
}
