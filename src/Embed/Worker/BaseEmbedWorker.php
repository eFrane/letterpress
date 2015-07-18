<?php namespace EFrane\Letterpress\Embed\Worker;

use DOMDocument;

use Embed\Adapters\AdapterInterface;

abstract class BaseEmbedWorker implements EmbedWorker
{
  use \EFrane\Letterpress\Markup\DOMManipulation;

  protected $bbcode = false;

  protected $urlRegex = '';

  protected $matches = [];

  protected $doc;

  public function apply(AdapterInterface $adapter)
  {
    return $this->importCode($this->doc, $adapter->getCode());
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
    return $this->getCodeRegex('[', ']');
  }

  public function getXMLCodeRegex()
  {
    return $this->getCodeRegex('<', '>');
  }

  public function isBBCodeEnabled()
  {
    return $this->bbcode;
  }

  public function setDocument(DOMDocument $document)
  {
    $this->doc = $document;
  }

  /**
   * @return string
   **/
  protected function getTagName()
  {
    $tagName = explode('\\', get_called_class());
    $tagName = strtolower($tagName[count($tagName) - 1]);

    return $tagName;
  }

  /**
   * @return string
   **/
  protected function getCodeRegex($openingDelimiter = '<', $closingDelimiter = '>')
  {
    $tagName = $this->getTagName();
    $urlRegex = $this->prepareURLRegex();

    $openingDelimiter = preg_quote($openingDelimiter, '/');
    $closingDelimiter = preg_quote($closingDelimiter, '/');

    return $openingDelimiter.$tagName.$closingDelimiter.$urlRegex.$openingDelimiter."/".$tagName.$closingDelimiter;
  }
}
