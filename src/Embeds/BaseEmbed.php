<?php namespace EFrane\Letterpress\Embeds;

abstract class BaseEmbed implements Embed
{
  protected $bbcode = false;

  protected $urlRegex = '//';

  public function getURLRegex()
  {
    if (!is_string($this->urlRegex) || $this->urlRegex[0] !== '/')
      throw new EmbedException("Invalid regular expression.");

    return $this->urlRegex;
  }

  public function getBBCode()
  {
    if ($this->bbcode)
    {
      $tagName  = strtolower(basename(get_called_class()));

      $urlRegex = explode('/', $this->getURLRegex());
      $tagRegex = sprintf("/\[%s\]%s\[\/%s\]/%s", $tagName, $urlRegex[1], $tagName, $urlRegex);

      return $tagRegex;
    }

    return "";
  }
}
