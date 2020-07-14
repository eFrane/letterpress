<?php


use EFrane\Letterpress\LetterpressException;

class LetterpressExceptionTest extends TestCase
{
    public function testExceptionIsThrowable()
    {
        $this->expectException(LetterpressException::class);

        throw new LetterpressException();
    }
}
