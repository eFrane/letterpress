<?php namespace EFrane\Letterpress\Markup;

use DOMNode;

/**
 * Fix block quotes. More information on that can be found at:
 * http://alistapart.com/blog/post/more-thoughts-about-blockquotes-than-are-strictly-required
 *
 * In Markdown, blockquotes are usually written like:
 *
 * ```markdown
 * > Feelings! I have no time for them, no chance of them. 
 * > I pass my whole life, miss, in turning an immense pecuniary Mangle.
 * - Charles Dickens "A Tale of Two Cities"
 * ```
 *
 * Or, more descriptively: The quote text is prefixed with standard Markdown
 * blockquote marker, followed by the quote's author prefixed with a dash
 * and the corresponding work in quotes. Variations on the author/work information
 * may include:
 *
 * - a link containing the author's name as text and the source as link destination
 * - author and source separated by quotes as in the example above
 * - author and source separated by a colon instead of quotes 
 *   (e.g. Sherlock Holmes: A Study in Scarlet)
 *
 * By default, a Markdown parser would translate the above to:
 *
 * ```html
 * <blockquote>
 *   Feelings! I have no time for them, no chance for them.
 *   I pass my whole life, miss, in turning an immense pecuniary Mangle!.
 *   <ul>
 *     <li>Charles Dickens "A Tale of Two Cities"</li>
 *   </ul>
 * </blockquote>
 * ```
 * 
 * which apparently is not exactly semantically correct.
 *
 * The above mentioned ALA article describes a semantically better fitting
 * quote syntax:
 *
 * ```html
 * <figure>
 *   <blockquote>
 *     Feelings! I have no time for them, no chance for them.
 *     I pass my whole life, miss, in turning an immense pecuniary Mangle!.
 *   </blockquote>
 *   <figcaption>Charles Dickens <cite>A Tale of Two Cities</cite></figcaption>
 * </figure>
 * ```
 *
 * Unfortunately, this modifier can't do magic. Which means that even though it
 * tries to fix most blockquotes, it only does so by looking for the basic
 * DOM pattern of `blockquote > ul`. It will convert that to
 * `figure > (blockquote + figcaption)`. It will however **not** do the `cite`
 * addition. Links in sources will be copied.
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class BlockQuoteModifier extends BaseModifier // implements Modifier
{
  protected function candidateCheck(DOMNode $candidate)
  {
    return strcmp($candidate->nodeName, 'blockquote') == 0
        && $candidate->hasChildren()
        && strcmp($candidate->lastChild->nodeName, 'ul') == 0
  }

  protected function candidateModify(DOMNode $parent, DOMNode $candidate)
  {
    $figure = $this->doc->createElement('figure');
    $quoteContent = $this->doc->createNodeList();

    foreach ($candidate->childNodes as $contentNodeCandidate)
    {
      if (strcmp($contentNodeCandidate->nodeName, 'ul') !== 0)
        $quoteContent->addNode($contentNodeCandidate);
    }

    $figure->append($quoteContent);

    $figcaption = $this->doc->createElement('figcaption');
    $captionContent = $candidate->lastChild->firstChild->childNodes;
    $figcaption->append($captionContent);

    $figure->append($figcaption);

    $parent->insertBefore($figure, $candidate);
    
    $parent->removeChild($candidate->nextSibling);
    $parent->removeChild($candidate);
  }
}
