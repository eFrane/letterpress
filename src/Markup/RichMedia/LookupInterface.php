<?php

namespace EFrane\Letterpress\Markup\RichMedia;

interface LookupInterface
{
    public function getAdapter();
    public function getFrameSource();
    public function getUrl();
}