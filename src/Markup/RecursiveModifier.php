<?php namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;
use DOMNode;

abstract class RecursiveModifier extends BaseModifier
{
  public function modify(DOMDocumentFragment $fragment)
  {
    $this->doc = $fragment->ownerDocument;
    $fragment = $this->walk($fragment);
    return $fragment;
  }

  protected function walk(DOMNode $node)
  {
    foreach ($node->childNodes as $current)
    {
      // look for blockquote + ul
      if ($this->candidateCheck($current))
        $node = $this->candidateModify($node, $current);

      if ($current->hasChildNodes())
        $current = $this->walk($current);
    }

    return $node;
  }

  abstract protected function candidateCheck(DOMNode $candidate);
  abstract protected function candidateModify(DOMNode $parent, DOMNode $candidate);
}
