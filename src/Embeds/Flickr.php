<?php namespace EFrane\Letterpress\Embeds;

class Flickr extends SingleScriptEmbed
{
  protected $bbcode = true;
  protected $urlRegex = '(http(s)?:\/\/)?(www\.)?flickr.com\/.+';
}
