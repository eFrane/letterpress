<?php namespace EFrane\Letterpress\Markup;

use DOMNode;
use DOMDocument;
use DOMDocumentFragment;

class HeaderLevelModifier implements Modifier
{
  protected $maximumLevel = 0;
  
  protected $relevantHeadlineTags = [];
  protected $replaceTagName = '';

  protected $doc = null;

  public function __construct($maximumLevel)
  {
    $this->maximumLevel = ($maximumLevel < 1) ? 1 : $this->maximumLevel;
    
    $levels = range(1, $this->maximumLevel);
    $this->relevantHeadlineTags = array_map(function ($el) {
      return sprintf('h%d', $el);
    }, $levels);

    $this->replaceTagName = sprintf('h%d', $maximumLevel);
  }

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
      if (in_array($current->nodeName, $this->relevantHeadlineTags))
      {
        $newNode = $this->doc->createElement($this->replaceTagName, $current->nodeValue);

        if (!is_null($current->nextSibling))
        {
          $node->insertBefore($newNode, $current->nextSibling);
        } else
        {
          $node->append($newNode);
        }

        $node->removeChild($current);
      }

      if ($current->hasChildNodes())
        $this->walk($current);

      $previous = $current;
    }

    return $node;
  }
}
