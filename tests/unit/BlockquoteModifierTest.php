<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class BlockquoteModifierTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected function _before()
    {
        Config::init();
        $this->lp = new Letterpress;
    }

    protected function _after()
    {
    }

    // tests
    public function testChangeMatchedBlockQuote()
    {
        $input = "> Feelings! I have no time for them, no chance of them.\n"
               . "> I pass my whole life, miss, in turning an immense pecuniary Mangle.\n"
               . "- Charles Dickens \"A Tale of Two Cities\"";

        $expected = "<figure><p>Feel&shy;ings! I have no time for them, no chance of them.<br>\n"
                  . "I pass my whole life, miss, in turn&shy;ing an immense pecu&shy;ni&shy;ary Mangle.</p>\n"
                  . "\n"
                  . "<figcaption>Charles Dick&shy;ens &ldquo;A Tale of Two Cities&rdquo;</figcaption></figure>";

        $output = $this->lp->press($input);
        $this->assertEquals($output, $expected);
    }

    public function testUnchangedNonMatchedBlockquote()
    {
        $input = "> This is just a normal blockquote.";

        $expected = "<blockquote>\n<p>This is just a normal block&shy;quote.</p>\n</blockquote>";

        $output = $this->lp->press($input);
        $this->assertEquals($output, $expected);
    }
}
