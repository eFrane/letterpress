<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;
use Masterminds\HTML5;

trait DOMManipulation
{
    protected function hasChildNodeWithTagName(DOMNode $node, $tagName)
    {
        if (!$node->hasChildNodes()) {
            return false;
        }

        foreach ($node->childNodes as $child) {
            if (strcmp($child->nodeName, $tagName) == 0) {
                return true;
            }
        }

        return false;
    }

    protected function hasFollowingSiblingWithTagName(DOMNode $node, $tagName)
    {
        if (!is_null($node->nextSibling)) {
            $actualNode = $node->nextSibling;
        } else {
            return false;
        }

        if (strcmp($actualNode->nodeName, $tagName) === 0) {
            return true;
        }

        return false;
    }
}
