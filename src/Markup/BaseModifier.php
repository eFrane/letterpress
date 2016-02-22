<?php

namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;

abstract class BaseModifier implements Modifier
{
    protected $doc = null;

    public function modify(DOMDocumentFragment $fragment)
    {
        $this->doc = $fragment->ownerDocument;
    }
}
