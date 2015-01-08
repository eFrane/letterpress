<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class LanguageCodeModifierTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected function _before()
    {
        Config::init('config');
        $this->lp = new Letterpress;
    }

    protected function _after()
    {
    }

    // tests
    public function testNoModifyWithDefaultConfig()
    {
        $input = "Some input";
        $expected = "<p>Some input</p>";

        $output = $this->lp->press($input);

        $this->assertEquals($expected, $output);
    }

    public function testMofifyWhenEnabled()
    {
        $input = "Some input";
        $expected = "<div lang=\"en\"><p>Some input</p></div>";

        $output = $this->lp->press($input, ['letterpress.markup.addLanguageInfo' => true]);

        $this->assertEquals($expected, $output);
    }
}
