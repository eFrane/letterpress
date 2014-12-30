<?php namespace EFrane\Letterpress\Embeds;

use DOMDocumentFragment;
use DOMNode;
use DOMText;

use \HTML5;
use Embed\Embed as OEmbedAdapter;

class EmbedRepository
{
  protected $embeds = [];
  protected $html = null;

  public function __construct(array $embeds = [])
  {
    foreach ($embeds as $embedCandidate)
    {
      if (class_exists($embedCandidate))
      {
        try
        {
          $embedInstance = new $embedCandidate;
          if ($embedInstance instanceof EFrane\Letterpress\Embeds\Embed)
            $this->embeds[] = $embedInstance;
        } catch(\Exception $e)
        {
          continue;
        }
      }
    }
  }

  /**
   * Embeds are very special modifications in the sense that they only *attack*
   * text nodes. Thus, the apply process of embeds can be greatly simplified
   * from the one of ordinary modifiers as to expecting a regular expression to
   * match for the node instead of testing of DOMNode attributes and relationships.
   *
   * Upon apply'ing repository walks through the supplied document fragment
   * and checks the enabled embed's tests for each text node longer than 2 characters.
   * This reduces the risk of checking text nodes created from line breaks or multiple
   * spaces in a row.
   *
   * @param DOMDocumentFragment the fragment
   * @return DOMDocumentFragment the fixed fragment
   **/
  public function apply(DOMDocumentFragment $fragment)
  {
    // foreach ($this->embeds as $embed)
    // {
    //   if ($embed->test(HTML5::saveHTML($fragment)))
    //     $embed->apply($fragment, $template);
    // };

    foreach ($fragment->childNodes as $node)
      $this->walk($node);
  }

  protected function walk(DOMNode $node)
  {
    if (($node instanceof DOMText) && $this->test($node))
    {

    }

    if ($node->hasChilNodes())
    {
      foreach ($node->childNodes as $child)
      {
        $child = $this->walk($node);
      }
    }

    return $node;
  }

  protected function test(DOMText $node)
  {
    foreach ($this->embeds as $embed)
    {
      if ($embed->test($node->nodeValue))
      {
        
      }
    }
  }
}
