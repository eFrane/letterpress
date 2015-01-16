<?php namespace EFrane\Letterpress\Embeds;

use DOMDocument;

use Embed\Adapters\AdapterInterface;

abstract class BaseEmbed implements Embed
{
  use \EFrane\Letterpress\Markup\DOMManipulation;

  protected $bbcode = false;

  protected $urlRegex = '';

  protected $matches = [];

  protected $doc;

  public function apply(AdapterInterface $adapter)
  {
    return $this->importCode($adapter->getCode());
  }

  protected function prepareURLRegex()
  {
    return sprintf('(?P<url>%s)', $this->urlRegex);
  }

  public function getURLRegex()
  {
    return sprintf('/%s/i', $this->prepareURLRegex());
  }

  public function getBBCodeRegex()
  {
    $tagName = explode('\\', get_called_class());
    $tagName = strtolower($tagName[count($tagName) - 1]);

    $urlRegex = $this->prepareURLRegex();

    $tagRegex = sprintf("/\[%s.*?\]%s\[\/%s\]/i", $tagName, $urlRegex, $tagName);

    return $tagRegex;
  }

  public function isBBCodeEnabled()
  {
    return $this->bbcode;
  }

  public function setDocument(DOMDocument $document)
  {
    $this->doc = $document;
  }
}
