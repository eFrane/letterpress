<?php namespace EFrane\Letterpress\Embeds;

use DOMDocumentFragment;

use Embed\Adapters\AdapterInterface;

/**
 * Twitter sends the complete embed code including their magic widget
 * code on every oembed request. Which is great for single tweets but kinda
 * not so cool for other purposes.
 * 
 * They do however recommend an obvious solution: delete that script tag on all
 * but one embed. Which is what the SingleScriptEmbed class does.
 **/
class Twitter extends SingleScriptEmbed
{
  protected $bbcode = true;
  protected $urlRegex = 'https?:\/\/(www\.)?twitter\.com\/(?:#!\/)?(\w+)\/status(es)?\/(\d+)';
}
