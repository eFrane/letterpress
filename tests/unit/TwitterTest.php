<?php

use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config;

class TwitterTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected $baseInput = "[twitter]https://twitter.com/eFrane/status/6776044466[/twitter]";

    protected function _before() 
    {
        Config::init();

        $this->lp = new Letterpress;
    }

    protected function _after() {}

    public function testNothing()
    {
      // NOTE: this is just here to make this test pass
    }
}
