<?php namespace EFrane\Letterpress\Embeds;

use Embed\Adapters\AdapterInterface;

interface Embed
{
  /**
   * @param Embed\Adapters\AdapterInterface
   * @return DOMDocumentFragment
   **/
  public function apply(AdapterInterface $adapter);
}
