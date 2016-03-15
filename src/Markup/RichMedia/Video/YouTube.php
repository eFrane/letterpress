<?php namespace EFrane\Letterpress\Markup\RichMedia\Video;

use EFrane\Letterpress\Markup\RichMedia\MediaModifier;

class YouTube extends MediaModifier
{
    public function setLinkPattern()
    {
        $this->linkPattern = '/(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+/';
    }
}
