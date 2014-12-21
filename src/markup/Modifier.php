<?php namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;

interface Modifier
{
  public function modify(DOMDocumentFragment $fragment);
}
