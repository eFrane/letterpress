<?php namespace EFrane\Letterpress\Embeds;

class Twitter extends BaseEmbed
{
  protected $bbcode = true;
  protected $urlRegex = 'https?:\/\/twitter\.com\/(?:#!\/)?(\w+)\/status(es)?\/(\d+)';
}
