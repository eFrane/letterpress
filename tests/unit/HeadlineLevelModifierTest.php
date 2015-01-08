<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class HeadlineLevelModifierTest extends \Codeception\TestCase\Test
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
    public function testHeadlineLevelChange()
    {
        $input    = "# This will remain a level 1 headline";
        $expected = "<h1>This will remain a level 1 head&shy;line</h1>";

        $output = $this->lp->press($input);
        $this->assertEquals($expected, $output);

        $input    = "# This will become a level 3 headline";
        $expected = "<h3>This will become a level 3 head&shy;line</h3>";

        $output = $this->lp->press($input, ['letterpress.markup.maxHeadlineLevel' => 3]);
        $this->assertEquals($expected, $output);
    }
    
}
