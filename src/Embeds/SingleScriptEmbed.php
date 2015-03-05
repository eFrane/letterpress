<?php namespace EFrane\Letterpress\Embeds;

use \DOMDocumentFragment;
use Embed\Adapters\AdapterInterface;

abstract class SingleScriptEmbed extends BaseEmbed
{  
  protected static $instances = 0;

  public function __construct()
  {
    static::$instances++;
  }

  public function apply(AdapterInterface $adapter)
  {
    $code = $this->importCode($this->doc, $adapter->getCode());

    if (static::$instances > 1)
      $code = $this->removeScriptTag($code);

    return $code;
  }

  protected function removeScriptTag(DOMDocumentFragment $code)
  {
    return $code;
  }
}
