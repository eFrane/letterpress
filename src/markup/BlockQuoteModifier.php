<?php namespace EFrane\Letterpress\Markup;

use DOMNode;

/**
 * Fix block quotes. More information on that can be found at:
 * http://alistapart.com/blog/post/more-thoughts-about-blockquotes-than-are-strictly-required
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class BlockQuoteModifier extends BaseModifier // implements Modifier
{
  protected function walk(DOMNode $node)
  {
    return $node;
  }
}
