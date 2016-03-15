<?php

namespace EFrane\Letterpress\Markup\RichMedia\Social;

use EFrane\Letterpress\Markup\RichMedia\MediaModifier;

class Twitter extends MediaModifier
{
    protected function setLinkPattern()
    {
        $this->linkPattern = '(https?:\/\/)?(www\.)?twitter\.com\/(?:#!\/)?(\w+)\/status(es)?\/(\d+)';
    }
}
