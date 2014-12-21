<?php namespace EFrane\Letterpress\Markup;

use DOMNode;

class HeadlineLevelModifier extends BaseModifier // implements Modifier
{
  protected $maxLevel = 0;
  
  protected $relevantHeadlineTags = [];
  protected $replaceTagName = '';

  protected $doc = null;

  public function __construct($maxLevel)
  {
    $this->maxLevel = ($maxLevel < 1) ? 1 : $this->maxLevel;
    
    $levels = range(1, $this->maxLevel);
    $this->relevantHeadlineTags = array_map(function ($el) {
      return sprintf('h%d', $el);
    }, $levels);

    $this->replaceTagName = sprintf('h%d', $maxLevel);
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
