<?php namespace EFrane\Letterpress\Embed\Worker;

class FlickrWorker extends SingleScriptEmbedWorker
{
  protected $bbcode = true;
  protected $urlRegex = '(http(s)?:\/\/)?(www\.)?flickr.com\/.+';
}
