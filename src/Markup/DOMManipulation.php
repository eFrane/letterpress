<?php

namespace EFrane\Letterpress\Markup;

use DOMDocument;
use DOMDocumentFragment;
use DOMElement;
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

    protected function hasFollowingSiblingWithTagName(DOMNode $node, $tagName, $discardTextNodes = true)
    {
        if (!is_null($node->nextSibling)) {
            $actualNode = $node->nextSibling;
        } else {
            return false;
        }

        if ($discardTextNodes) {
            while ($actualNode->nodeType == XML_TEXT_NODE) {
                if (!is_null($node->nextSibling)) {
                    $actualNode = $node->nextSibling;
                }
            }
        }

        if (strcmp($actualNode->nodeName, $tagName) === 0) {
            return true;
        }

        return false;
    }
}
