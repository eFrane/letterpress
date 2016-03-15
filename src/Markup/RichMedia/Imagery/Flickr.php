<?php

namespace aEFrane\Letterpress\Markup\RichMedia\Imagery;

use EFrane\Letterpress\Markup\RichMedia\MediaModifier;

class Flickr extends MediaModifier
{
    protected function setLinkPattern()
    {
        $this->linkPattern = '/(https?:\/\/)?(www\.)?flickr.com\/.+/';
    }
}