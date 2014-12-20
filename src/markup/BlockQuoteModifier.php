<?php namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;

/**
 * Fix block quotes. More information on that can be found at:
 * http://alistapart.com/blog/post/more-thoughts-about-blockquotes-than-are-strictly-required
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class BlockQuoteModifier implements Modifier
{
  public function modify(DOMDocumentFragment $fragment)
  {
    return $fragment;       
  }
}
