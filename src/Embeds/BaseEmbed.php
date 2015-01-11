<?php namespace EFrane\Letterpress\Embeds;

use Embed\Adapters\AdapterInterface;

abstract class BaseEmbed implements Embed
{
  protected $bbcode = false;

  protected $urlRegex = '';

  protected $matches = [];

  public function apply(AdapterInterface $adapter)
  {
    $code = $adapter->getCode();
    return HTML5::loadHTMLFragment($code);
  }

  protected function prepareURLRegex()
  {
    return sprintf('(?P<url>%s)', $this->urlRegex);
  }

  public function getURLRegex()
  {
    return sprintf('/^%s$/i', $this->prepareURLRegex());
  }

  public function getBBCodeRegex()
  {
    $tagName = explode('\\', get_called_class());
    $tagName = strtolower($tagName[count($tagName) - 1]);

    $urlRegex = $this->prepareURLRegex();

    $tagRegex = sprintf("/^\[%s.*?\]%s\[\/%s\]$/i", $tagName, $urlRegex, $tagName);

    return $tagRegex;
  }

  public function isBBCodeEnabled()
  {
    return $this->bbcode;
  }
}
