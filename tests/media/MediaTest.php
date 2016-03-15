<?php

use EFrane\Letterpress\Markup\RichMedia\LookupInterface;
use EFrane\Letterpress\Markup\RichMedia\Repository;

abstract class MediaTest extends MarkupModifierTest
{
    /**
     * @return \EFrane\Letterpress\Markup\RichMedia\Repository
     **/
    protected function generateRepository($pattern, $frameSource)
    {
        $mock = $this->getMockBuilder(LookupInterface::class)
            ->setMethods(['getFrameSource', 'getUrl', 'getAdapter'])
            ->getMock();

        $mock->expects($this->once())->method('getFrameSource')->willReturn($frameSource);

        $repo = new Repository([
            $pattern => $mock,
        ]);

        return $repo;
    }
}
