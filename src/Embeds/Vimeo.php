<?php namespace EFrane\Letterpress\Embeds;

class Vimeo extends BaseEmbed
{
  protected $bbcode = true;
  protected $urlRegex = '(http(s)?:\/\/)?(www\.)?vimeo\.com\/(\d+)';
}
