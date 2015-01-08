<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class RemoveEmptyNodesModifierTest extends \Codeception\TestCase\Test
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
    public function testElementRemove()
    {
        $input = "<br><br><p></p>";
        $expectedMarkupOnly = "<br><br>";
        $expectedAll = "<p><br><br></p>";

        $output  = $this->lp->markup($input);
        $output2 = $this->lp->press($input);

        $this->assertEquals($output, $expectedMarkupOnly);
        $this->assertEquals($output2, $expectedAll);
    }

}
