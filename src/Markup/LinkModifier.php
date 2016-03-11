<?php namespace EFrane\Letterpress\Markup;

use DOMNode;

class LinkModifier extends RecursiveModifier
{
    /**
     * Replacer functions for link modifiers can accept two arguments:
     *
     * 1) $href string the href attribute value of the currently tested link
     * 2) $doc \DOMDocument the base document for the current modification pass
     *
     * @var callable replacer function
     */
    protected $replacer = null;

    public function __construct(callable $replacer)
    {
        $this->replacer = $replacer;
    }

    public function candidateCheck(DOMNode $candidate)
    {
        /* @var $candidate \DOMElement */
        return $this->nodeType()->isElement($candidate) && $candidate->nodeName == 'a' && $candidate->hasAttribute('href');
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        /* @var $candidate \DOMElement */
        $replacement = call_user_func($this->replacer, $candidate->getAttribute('href'), $this->doc);

        // do nothing if null or a falsy value was returned
        if (is_null($replacement) || !$replacement) return;

        if ($this->nodeType()->isElement($replacement)) {
            $parent->replaceChild($replacement, $candidate);
        } else {
            // if no element was returned, place the return value into a text node
            $wrapper = $this->doc->createTextNode($replacement);
            $parent->replaceChild($wrapper, $candidate);
        }
    }
}