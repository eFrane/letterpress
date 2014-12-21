<?php namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;
use DOMNode;

interface Modifier
{
  public function modify(DOMDocumentFragment $fragment);
  protected function walk(DOMNode $node);
}
