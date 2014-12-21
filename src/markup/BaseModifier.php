<?php namespace EFrane\Lettepress\Markup;

use DOMDocumentFragment;
use DOMNode;

abstract class BaseModifier implementes Modifier
{
  protected $doc = null;

  public function modify(DOMDocumentFragment $fragment)
  {
    $this->doc = $fragment->ownerDocument;
    $this->walk($fragment);
  }

  protected function walk(DOMNode $node)
  {
    foreach ($node->childNodes as $current)
    {
      // look for blockquote + ul
      if ($this->candidateCheck($current))
        $this->candidateModify($node, $current);

      if ($current->hasChildren())
        $this->walk($current);
    }
  }

  abstract protected function candidateCheck(DOMNode $candidate);
  abstract protected function candidateModify(DOMNode $parent, DOMNode $candidate);
}
