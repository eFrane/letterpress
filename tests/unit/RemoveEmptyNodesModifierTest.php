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
        Config::init();

        $this->lp = new Letterpress;
    }

    protected function _after()
    {
    }

    // tests
    public function testElementRemoveMarkupOnly()
    {
        $input = "<br><br><p></p>";
        $expectedMarkupOnly = "<br><br>";
    
        $output  = $this->lp->markup($input);
        $this->assertEquals($output, $expectedMarkupOnly);        
    }

    public function testElementRemoveAllSteps()
    {
        $input = "<br><br><p></p>";
        $expectedAll = "<p><br><br></p>";
        
        $output = $this->lp->press($input);
        $this->assertEquals($output, $expectedAll);
    }
}
