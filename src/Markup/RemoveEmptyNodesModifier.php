<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;

class RemoveEmptyNodesModifier extends RecursiveModifier
{
    protected $allowedEmptyTagNames = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    /*
     * NOTE: This SHOULD be sufficient since all other text containers
     *       SHOULD not generally be modified in a way that empties them
     *       unpurposely.
     */
    protected $textContainerTagNames = [
        'p',
    ];

    /**
     * There's basically two ways of telling that a node is empty:.
     *
     * 1. In the simple case, the node is empty if it isn't defined as a void
     *    element in the spec (http://www.w3.org/TR/html5/syntax.html#void-elements)
     *
     * 2. In the complex case, a node is a text container which only contains
     *    zero-length text nodes or whitespace-text nodes.
     **/
    public function candidateCheck(DOMNode $candidate)
    {
        if ($candidate->nodeType !== $this->nodeType()->element()) {
            return false;
        }

        if (in_array($candidate->nodeName, $this->allowedEmptyTagNames)) {
            return false;
        }

        if (!$candidate->hasChildNodes()) {
            return true;
        } else {
            if (preg_match('/^\s*$/', $candidate->textContent)) {
                return true;
            }
        }

        return false;
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $parent->removeChild($candidate);

        return $parent;
    }
}
