<?php

namespace EFrane\Letterpress\Markup;

use DOMDocument;
use DOMElement;
use DOMNode;
use Masterminds\HTML5;

trait DOMManipulation
{
    protected function renameAttribute(DOMElement $node, $attributeName, $newName)
    {
        $node->setAttribute($newName, $node->getAttribute($attributeName));
        $node->removeAttribute($attributeName);
    }

    protected function concatenateFragments(DOMDocument $document, array $fragments)
    {
        $returnedFragment = $document->createDocumentFragment();

        foreach ($fragments as $fragment) {
            $imported = $document->importNode($fragment);
            $returnedFragment->appendChild($imported);
        }

        return $returnedFragment;
    }

    protected function createTag(DOMDocument $document, $tagName, $value = null, array $attributes = [])
    {
        $element = (is_string($value))
            ? $document->createElement($tagName, $value)
            : $document->createElement($tagName);

        $this->setAttributes($element, $attributes);

        return $element;
    }

    protected function setAttributes(DOMElement $element, array $attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            // check if it's a void attribute

            // NOTE: Because of the way, PHP's DOM works, void attributes
            //       will be inserted as value="value" attributes which despite
            //       being valid is not exactly beautiful.
            if (is_int($attribute)) {
                $element->setAttribute($value, $value);
            } else {
                $element->setAttribute($attribute, $value);
            }
        }
    }

    protected function importCode(DOMDocument $document, $code)
    {
        $fragment = (new HTML5())->loadHTMLFragment($code);

        return $document->importNode($fragment, true);
    }

    protected function hasChildNodeWithTagName(DOMNode $node, $tagName)
    {
        if (!$node->hasChildNodes()) return false;

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
