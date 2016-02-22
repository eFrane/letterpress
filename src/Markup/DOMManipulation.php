<?php

namespace EFrane\Letterpress\Markup;

use DOMDocument;
use DOMElement;
use HTML5;

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

    protected function importCode(DOMDocument $document, $code)
    {
        $fragment = HTML5::loadHTMLFragment($code);

        return $document->importNode($fragment, true);
    }

    protected function setAttributes(DOMElement $element, array $attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            // check if it's a void attribute
      if (is_int($attribute)) {
          $element->setAttribute($value);
      } else {
          $element->setAttribute($attribute, $value);
      }
        }
    }
}
