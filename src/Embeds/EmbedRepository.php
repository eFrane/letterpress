<?php namespace EFrane\Letterpress\Embeds;

use DOMDocumentFragment;
use DOMNode;
use DOMText;

use \HTML5;
use Embed\Embed as OEmbedAdapter;

class EmbedRepository
{
  protected $embeds = [];
  protected $doc = null;

  public function __construct(array $embeds = [])
  {
    foreach ($embeds as $embedCandidate)
    {
      if (class_exists($embedCandidate))
      {
        try
        {
          $embedInstance = new $embedCandidate;

          if ($embedInstance instanceof Embed)
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
    $this->doc = $fragment->ownerDocument;

    $fragment = $this->walk($fragment);
    return $fragment;
  }

  protected function walk(DOMNode $node)
  {
    foreach ($node->childNodes as $current)
    {
      if (is_a($current, 'DOMText') && !$current->hasChildNodes())
        $current = $this->doEmbeds($current);

      if ($current->hasChildNodes())
        $current = $this->walk($current);
    }

    return $node;
  }

  protected function doEmbeds(DOMText $element)
  {
    foreach ($this->embeds as $embed)
    {
      $urls = [];

      $embed->setDocument($element->ownerDocument);

      $regex = '';
      if ($embed->isBBCodeEnabled())
      {
        $regex = $embed->getBBCodeRegex();        
      } else
      {
        $regex = $embed->getURLRegex();
      }

      preg_match($regex, $element->nodeValue, $matches);
      if (isset($matches['url']) && strlen($matches['url']) > 0) 
          $urls[$matches[0]] = $matches['url'];

      foreach ($urls as $match => $url)
      {
        if (parse_url($url, PHP_URL_SCHEME) === null)
          $url = sprintf('https://%s', $url);

        $adapter = null;
        try
        {
          $adapter = OEmbedAdapter::create($url);
        } catch(\Exception $e)
        {
          $adapter = false;
        }

        if (!is_bool($adapter) && strlen($adapter->getCode()) > 0)
        {
          $returnedFragment = $embed->apply($adapter);
          $returnedFragment = $this->doc->importNode($returnedFragment, true);

          /**
           * Once the replacement code is acquired, one of two possibilities apply:
           *
           * 1. the passed text node only contained the link reference
           *
           * In this case, the text node will be replaced with the returned
           * DOMDocument fragment from apply().
           *
           * 2. the passed text node contains *more* than the link reference
           *
           * In this case, the acquired fragment will be inserted before the 
           * text node. The bbcode or link in the text node will be removed.
           **/
          if (trim(strlen($element->nodeValue)) == strlen($match))
          {
            // case 1
            if (!is_null($element->parentNode->parentNode))
            {
              $element->parentNode->parentNode->insertBefore($returnedFragment, $element->parentNode);
              $element->parentNode->parentNode->removeChild($element->parentNode);
            } else
            {
              $this->doc->insertBefore($element->parentNode);
              $this->doc->removeChild($element->parentNode);
            }
          } else
          {
            // case 2
            $this->doc->insertBefore($returnedFragment, $element);

            $element->nodeValue = str_replace($match, '', $element->nodeValue);            
            if (strcmp($element->nodeValue, '') === 0)
              $this->doc->removeNode($element);
          }
        }
      }
    }

    return $element;
  }
}
