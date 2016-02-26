<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;

class HeadlineLevelModifier extends RecursiveModifier // implements Modifier
{
    protected $maxLevel = 0;

    protected $relevantHeadlineTags = [];
    protected $replaceTagName = '';

    public function __construct($maxLevel)
    {
        $this->maxLevel = ($maxLevel < 1) ? 1 : intval($maxLevel);

        $levels = range(1, $this->maxLevel);
        $this->relevantHeadlineTags = array_map(function ($el) {
            return sprintf('h%d', $el);
        }, $levels);

        $this->replaceTagName = sprintf('h%d', $maxLevel);
    }

    protected function candidateCheck(DOMNode $candidate)
    {
        return in_array($candidate->nodeName, $this->relevantHeadlineTags);
    }

    protected function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $newNode = $this->doc->createElement($this->replaceTagName, $candidate->nodeValue);

        $parent->insertBefore($newNode, $candidate);

        $parent->removeChild($candidate);

        return $parent;
    }
}
