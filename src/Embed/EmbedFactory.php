<?php namespace EFrane\Letterpress\Embed;

use DOMDocumentFragment;

use DOMNode;
use DOMElement;
use DOMText;

use \HTML5;
use Embed\Embed as OEmbedAdapter;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

use EFrane\Letterpress\Embed\Worker\EmbedWorker;

/**
 * @package Embeds
 */
class EmbedFactory 
{
  /**
   * @var array EFrane\Letterpress\Embeds\Embed
   **/
  protected $embeds = [];
  protected $repository = null;
  protected $doc = null;

  public function __construct(array $embeds = [])
  {
    // setup workers
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
   * Embeds are very special modifications in the sense that they only apply to
   * text nodes. Thus, the apply process of embeds can be greatly simplified
   * from the one of ordinary modifiers as to expecting a regular expression to
   * match for the node instead of testing of DOMNode attributes and relationships.
   *
   * Upon `apply`-ing the repository walks through the supplied document fragment
   * and checks the enabled embed's tests for each text node longer than 2 characters.
   * This reduces the risk of checking text nodes created from line breaks or multiple
   * spaces in a row.
   *
   * @param DOMDocumentFragment the fragment
   * @return DOMDocumentFragment the enhanced fragment
   **/
  public function run(DOMDocumentFragment $fragment, EmbedRepository $repository)
  {
    $this->doc = $fragment->ownerDocument;
    $this->repository = $repository;

    $fragment = $this->walk($fragment);
    return $fragment;
  }

  protected function walk(DOMNode $node)
  {
    foreach ($node->childNodes as $current)
    {
      if (is_a($current, 'DOMText') && !$current->hasChildNodes())
        $current = $this->findEmbeds($current);

      if ($current->hasChildNodes())
        $current = $this->walk($current);
    }

    return $node;
  }

  protected function findEmbeds(DOMText $element)
  {
    foreach ($this->embeds as $embed)
    {
      $urls = [];

      $embed->setDocument($element->ownerDocument);

      $regex = ($embed->isBBCodeEnabled())
        ? $regex = $embed->getBBCodeRegex()
        : $regex = $embed->getURLRegex();

      preg_match($regex, $element->nodeValue, $matches);
      if (isset($matches['url']) && strlen($matches['url']) > 0)
        $urls[$matches[0]] = $matches['url'];

      foreach ($urls as $match => $url)
      {
        try
        {
          $fragment = $this->getEmbedFragment($embed, $url);
          $element = $this->applyMatchedURL($element, $fragment, $match);
        } catch (LetterpressException $e)
        {
          if (Config::get('letterpress.media.silentfail'))
          {
            return $element;
          } else
          {
            throw $e;
          }
        }
      }
    }

    return $element;
  }

  protected function getEmbedFragment(EmbedWorker $embed, $url)
  {
    /**
     * add URL scheme if necessary
     *
     * this defaults to HTTPS and it's totally okay if things fail because
     * the requested service does not support https
     **/
    if (parse_url($url, PHP_URL_SCHEME) === null)
      $url = sprintf('https://%s', $url);

    try
    {
      $adapter = OEmbedAdapter::create($url);
      $applied = $embed->apply($adapter);

      return $this->repository->add($applied);
    } catch(\Exception $e)
    {
      $message = "Embed Adapter acquisition failed:\n\n";
      throw new LetterpressException($message.$e->getMessage());
    }
  }

  protected function applyMatchedURL(DOMText $element, DOMDocumentFragment $fragment, $match)
  {
    // find the enclosing element
    $enclosing = $element;

    do
    {
      $enclosing = $enclosing->parentNode;
    } while (!($enclosing instanceof DOMElement));

    // manipulate text node accordingly
    if (strcmp($element->nodeValue, $match) == 0)
    {
      if ($enclosing->parentNode)
      {
        $enclosing = $enclosing->parentNode->replaceChild($fragment, $enclosing);
      } else
      {
        $enclosing = $enclosing->replaceChild($fragment, $element);
      }
    } else
    {
      $element->nodeValue = str_replace($match, '', $element->nodeValue);
      $enclosing = $enclosing->appendChild($fragment);
    }

    return $enclosing;
  }
}