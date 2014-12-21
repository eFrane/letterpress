<?php namespace EFrane\Lettepress\Markup;

abstract class BaseModifier implementes Modifier
{
  protected $doc = null;

  public function modify(DOMDocumentFragment $fragment)
  {
    $this->doc = $fragment->ownerDocument;
    $fragment = $this->walk($fragment);

    return $fragment;
  }
}
