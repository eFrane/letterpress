<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;

/**
 * Fix block quotes. More information on that can be found at:
 * http://alistapart.com/blog/post/more-thoughts-about-blockquotes-than-are-strictly-required.
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
class BlockQuoteModifier extends RecursiveModifier // implements Modifier
{
    protected function candidateCheck(DOMNode $candidate)
    {
        $isBlockquoteWithChildNodes = strcmp($candidate->nodeName, 'blockquote') == 0
                               && $candidate->hasChildNodes();

        if ($isBlockquoteWithChildNodes) {
            foreach ($candidate->childNodes as $child) {
                if (strcmp($child->nodeName, 'ul') == 0) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $figure = $this->doc->createElement('figure');

        $captionContent = null;
        foreach ($candidate->childNodes as $contentNodeCandidate) {
            if (strcmp($contentNodeCandidate->nodeName, 'ul') !== 0) {
                $figure->appendChild($contentNodeCandidate->cloneNode(true));
            } else {
                $captionCandidate = $contentNodeCandidate->cloneNode(true);
                $captionContent = $this->extractCaptionContent($captionCandidate);
            }
        }

        if (!is_null($captionContent)) {
            $figcaption = $this->doc->createElement('figcaption');
            $figcaption->appendChild($captionContent);

            $figure->appendChild($figcaption);
        }

        $parent->insertBefore($figure, $candidate);

        $parent->removeChild($candidate);

        return $parent;
    }

    protected function extractCaptionContent(DOMNode $captionCandidate)
    {
        $captionContent = $this->doc->createDocumentFragment();

        if (strcmp($captionCandidate->nodeName, 'ul') == 0) {
            foreach ($captionCandidate->childNodes as $child) {
                if (strcmp($child->nodeName, 'li') == 0) {
                    foreach ($child->childNodes as $contentChild) {
                        $captionContent->appendChild($contentChild->cloneNode(true));
                    }

                    break;
                }
            }
        }

        return $captionContent;
    }
}
