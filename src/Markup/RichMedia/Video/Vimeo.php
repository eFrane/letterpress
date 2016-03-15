<?php

namespace EFrane\Letterpress\Markup\RichMedia\Video;

use EFrane\Letterpress\Markup\RichMedia\MediaModifier;

class Vimeo extends MediaModifier
{
    protected function setLinkPattern()
    {
        // TODO: Implement setLinkPattern() method.
        $this->linkPattern = '/(https?:\/\/)?(www\.)?vimeo\.com\/(\d+)/';
    }
}