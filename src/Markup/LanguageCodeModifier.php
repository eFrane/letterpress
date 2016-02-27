<?php

namespace EFrane\Letterpress\Markup;

use DOMNode;
use EFrane\Letterpress\Config;
use Efrane\Letterpress\LetterpressException;

/**
 * Add appropriate language codes to the produced markup.
 *
 * Even though this modifier produces valid markup, it is strongly recommended
 * to set the "lang"-attribute on the highest possible level of your
 * markup, e.g. ideally in the
 **/
class LanguageCodeModifier extends RecursiveModifier
{
    // TODO: check the list of block elements (W3C HTML5 TR...)
    protected $textContentElements = [
        'div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',

    ];

    protected function candidateCheck(DOMNode $candidate)
    {
        return true;
    }

    protected function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        // TODO: refactor locale checking as this is also being done in the typography fixer
        $languageCode = Config::get('letterpress.locale');
        if (!is_string($languageCode) || strlen($languageCode) < 2) {
            throw new LetterpressException('Invalid locale.');
        }

        $languageCode = strtolower(substr($languageCode, 0, 2));

        if (in_array($candidate->nodeName, $this->textContentElements)) {
            /* @var $candidate \DOMElement*/
            $candidate->setAttribute('lang', $languageCode);
        } else {
            $block = $this->doc->createElement('div');
            $block->setAttribute('lang', $languageCode);

            $candidateClone = $candidate->cloneNode(true);
            $block->appendChild($candidateClone);

            $candidate = $block;
        }

        return $candidate;
    }
}
