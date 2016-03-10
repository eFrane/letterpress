<?php namespace EFrane\Letterpress\Markup;

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

    protected function candidateCheck(DOMNode $candidate)
    {
        if ($candidate->nodeType != $this->nodeType()->text())
            return false;

        if ($candidate instanceof \DOMText && $candidate->isElementContentWhitespace())
            return false;

        if (!preg_match($this->pattern, $candidate->nodeValue, $this->matches))
            return false;

        return true;
    }

    protected function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        /* @var \DOMText $candidate this will always be a text node */
        $newContent = call_user_func($this->replacer, $candidate->wholeText, $this->matches);

        try {
            $candidate->replaceWholeText($newContent);
        } catch (\Exception $e) {
            $newNode = $this->doc->createTextNode($newContent);
            $parent->replaceChild($newNode, $candidate);
        }
    }
}