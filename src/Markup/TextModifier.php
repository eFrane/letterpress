<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;

class TextModifier extends RecursiveModifier
{
    /**
     * @var string
     */
    protected $pattern = '';

    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @var callable
     */
    protected $replacer = null;

    public function __construct($pattern, callable $replacer)
    {
        $this->pattern = $pattern;
        $this->replacer = $replacer;
    }

    public function candidateCheck(DOMNode $candidate)
    {
        if (!$this->nodeType()->isText($candidate)) {
            return false;
        }

        if (method_exists($candidate, 'isElementContentWhitespace') && $candidate->isElementContentWhitespace()) {
            return false;
        }

        if (!preg_match($this->pattern, $candidate->nodeValue, $this->matches)) {
            return false;
        }

        return true;
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        /* @var \DOMText $candidate this will always be a text node */
        $newContent = call_user_func($this->replacer, $candidate->wholeText, $this->matches);

        if (method_exists($candidate, 'replaceWholeText'))
            $candidate->replaceWholeText($newContent);
        else {
            $newNode = $this->doc->createTextNode($newContent);
            $parent->replaceChild($newNode, $candidate);
        }
    }
}