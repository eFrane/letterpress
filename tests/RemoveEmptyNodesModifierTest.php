<?php

use EFrane\Letterpress\Markup\RemoveEmptyNodesModifier;

class RemoveEmptyNodesModifierTest extends \MarkupModifierTest
{
    /**
     * @dataProvider emptyNodesData
     */
    public function testRemoveEmptyNodesModifier($actual, $expected)
    {
        $this->assertEquals($expected, $this->processor->process($actual));
    }

    public function emptyNodesData()
    {
        $bunchOfCode = '<span>Lorem ipsum</span><a href="#">I lead nowhere.</a>';

        $voidElements = collect(['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
            'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr', ])
            ->map(function ($tagName) use ($bunchOfCode) {
                $selfClosed = "<{$tagName} />";
                $open = "<{$tagName}>";
                $withAttributeClosed = "<{$tagName} data-test-attribute=\"test-value\" />";
                $withAttributeOpen = "<{$tagName} data-test-attribute=\"test-value\">";

                return [
                    [$selfClosed, $open],
                    [$open, $open],
                    [$withAttributeClosed, $withAttributeOpen],
                    [$withAttributeOpen, $withAttributeOpen],
                    [$open.$bunchOfCode, $open.$bunchOfCode],
                    [$bunchOfCode.$open, $bunchOfCode.$open],
                ];
            })->flatMap(function ($tagList) {
                return $tagList;
            });

        $textElements = collect(['p', 'div', 'span', 'h1', 'a'])
            ->map(function ($tagName) use ($bunchOfCode) {
                $open = "<{$tagName}>";
                $close = "</{$tagName}>";

                return [
                    [$open.$close, ''],
                    [$open.$bunchOfCode.$close, $open.$bunchOfCode.$close],
                    [$open.' '.$close, ''],
                    [$open."\n\n".$close, ''],
                ];
            })->flatMap(function ($tagList) {
                return $tagList;
            });

        return collect($voidElements)->merge($textElements)->toArray();
    }

    protected function setUnitUnderTest()
    {
        $this->processor->setModifiers([new RemoveEmptyNodesModifier()]);
    }
}
