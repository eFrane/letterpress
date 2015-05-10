<?php namespace EFrane\Letterpress\Embed\Worker;

use DOMDocument;
use Embed\Adapters\AdapterInterface;

interface EmbedWorker
{
  /**
   * @param Embed\Adapters\AdapterInterface
   * @return DOMDocumentFragment
   **/
  public function apply(AdapterInterface $adapter);

  public function setDocument(DOMDocument $document);
}
