<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;

class RemoveEmptyNodesModifier extends RecursiveModifier
{
    protected $allowedEmptyTagNames = [
    'area',
    'base',
    'br',
    'col',
    'embed',
    'hr',
    'img',
    'input',
    'keygen',
    'link',
    'meta',
    'param',
    'source',
    'track',
    'wbr',
  ];

  /*
   * NOTE: This SHOULD be sufficient since all other text containers
   *       SHOULD not generally be modified in a way that empties them
   *       unpurposely.
   */
  protected $textContainerTagNames = [
    'p',
  ];

  /**
   * There's basically two ways of telling that a node is empty:.
   * 
   * 1. In the simple case, the node is empty if it isn't defined as a void
   *    element in the spec (http://www.w3.org/TR/html5/syntax.html#void-elements)
   *    and doesn't contain any child nodes or it's a non-void tag which commonly
   *    does not have child elements but is solely defined with attributes 
   *    (e.g. iframe, script).
   *
   * 2. In the complex case, a node is a text container which only contains
   *    zero-length text nodes or whitespace.text nodes.
   **/
  protected function candidateCheck(DOMNode $candidate)
  {
      if ($candidate->nodeType !== XML_ELEMENT_NODE) {
          return false;
      }

      $simpleCheck = !$candidate->hasChildNodes()
                && (!in_array($candidate->tagName, $this->allowedEmptyTagNames)
                ||  $candidate->hasAttributes());

      if ($simpleCheck) {
          return true;
      }

    // FIXME: this does not work as expected
    /*
    $complexCheck = false;
    if ($candidate->hasChildNodes() 
    && in_array($candidate->tagName, $this->textContainerTagNames))
    {
      foreach ($candidate->childNodes as $childNode)
      {
        $complexCheck = (($childNode->nodeType === XML_TEXT_NODE
                     &&  strlen(trim($childNode->nodeValue)) === 0))
                     || $complexCheck;
      }
    }

    if ($complexCheck) return true;
    */

    return false;
  }

    protected function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $parent->removeChild($candidate);

        return $parent;
    }
}
