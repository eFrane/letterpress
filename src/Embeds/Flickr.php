<?php namespace EFrane\Letterpress\Embeds;

class Flickr extends BaseEmbed
{
  protected $bbcode = true;
  protected $urlRegex = '(http(s)?:\/\/)?(www\.)?flickr.com\/.+';
}
