<?php namespace EFrane\Letterpress\Embeds;

class Vimeo extends VideoEmbed
{
  protected $bbcode = true;
  protected $urlRegex = '(http(s)?:\/\/)?(www\.)?vimeo\.com\/(\d+)';
}
