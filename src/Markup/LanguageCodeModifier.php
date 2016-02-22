<?php

namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;
use DOMNode;
use EFrane\Letterpress\Config;
use Efrane\Letterpress\LetterpressException;

/**
 * Add appropriate language codes to the produced markup.
 * 
 * Even though this modifier produces valid markup, it is strongly recommended
 * to set the "lang"-attribute on the highest possible level of your
 * markup, e.g. ideally in the 
 **/
class LanguageCodeModifier extends BaseModifier // implements Modifier
{
    // if initialized, this always applies
  public function modify(DOMDocumentFragment $fragment)
  {
      parent::modify($fragment);

      $languageCode = Config::get('letterpress.locale');
      if (!is_string($languageCode) || strlen($languageCode) < 2) {
          throw new LetterpressException('Invalid locale.');
      }

      $languageCode = strtolower(substr($languageCode, 0, 2));

      $languageNode = $this->doc->createElement('div');
      $languageNode->setAttribute('lang', $languageCode);

      $fragment = $this->wrapFragment($fragment, $languageNode);

      return $fragment;
  }

  /**
   * Wrap a node and all of it's children in another node.
   *
   * @return DOMNode The wrapped node.
   **/
  protected function wrapFragment(DOMDocumentFragment $fragment, DOMNode $wrapNode)
  {
      $newFragment = $this->doc->createDocumentFragment();
      $wrapNode->appendChild($fragment->cloneNode(true));
      $newFragment->appendChild($wrapNode);

      return $newFragment;
  }
}
