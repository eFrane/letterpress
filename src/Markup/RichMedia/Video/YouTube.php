<?php namespace EFrane\Letterpress\Markup\RichMedia\Video;

use EFrane\Letterpress\Markup\RichMedia\Lookup;
use EFrane\Letterpress\Markup\RichMedia\MediaModifier;
use Masterminds\HTML5;

class YouTube extends MediaModifier
{
    public function enhanceMediaElement($url, \DOMDocument $doc)
    {
        // TODO: write some kind of embed cache/repository storing the codes, duration information, all that stuff


    }

    public function setLinkPattern()
    {
        $this->linkPattern = '/.+(youtube\.com\/watch|youtu\.be).+/';
    }
}