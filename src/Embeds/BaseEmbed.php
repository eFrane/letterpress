<?php namespace EFrane\Letterpress\Embeds;

abstract class BaseEmbed implements Embed
{
  protected $bbcode = false;

  protected $urlRegex = '';

  protected $matches = [];

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
