<?php

namespace EFrane\Letterpress\Markup;

use DOMDocumentFragment;
use DOMNode;

abstract class RecursiveModifier extends BaseModifier
{
    protected $domNodeType = null;

    public function nodeType()
    {
        if (is_null($this->domNodeType)) {
            $this->domNodeType = new DOMNodeType();
        }

        return $this->domNodeType;
    }

    public function modify(DOMDocumentFragment $fragment)
    {
        $this->doc = $fragment->ownerDocument;
        $fragment = $this->walk($fragment);

        return $fragment;
    }

    protected function walk(DOMNode $node)
    {
        foreach ($node->childNodes as $current) {
            /* @var $current DOMNode */

            if ($this->candidateCheck($current)) {
                $node = $this->candidateModify($node, $current);
            }

            if ($current->hasChildNodes()) {
                $this->walk($current);
            }
        }

        return $node;
    }

    abstract public function candidateCheck(DOMNode $candidate);

    abstract public function candidateModify(DOMNode $parent, DOMNode $candidate);
}
