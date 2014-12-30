<?php namespace EFrane\Letterpress\Embeds;

interface Embed
{
  /**
   * @param Embed\Adapters\AdapterInterface
   * @return String 
   **/
  public function apply(Embed\Adapters\AdapterInterface $adapter);
}
