<?php namespace EFrane\Letterpress\Embeds;

use DOMDocument;
use Embed\Adapters\AdapterInterface;

interface Embed
{
  /**
   * @param Embed\Adapters\AdapterInterface
   * @return DOMDocumentFragment
   **/
  public function apply(AdapterInterface $adapter);

  public function setDocument(DOMDocument $document);
}
