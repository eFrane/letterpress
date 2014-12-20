<?php namespace EFrane\Letterpress\Embeds;

interface Embed
{
  public function test($fragmentHTML);
  public function apply(DOMDocumentFragment $fragment, $template = '');
}
