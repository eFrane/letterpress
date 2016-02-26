<?php


use EFrane\Letterpress\LetterpressException;

class LetterpressExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage
     */
    public function testExceptionIsThrowable()
    {
        throw new LetterpressException;
    }
}
