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
    protected $textContentElements = [
        'address', 'article', 'aside', 'blockquote', 'dd', 'div', 'dl', 'figcaption',
        'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'li', 'main',
        'nav', 'noscript', 'output', 'p', 'pre', 'section', 'span',
    ];

    public function candidateCheck(DOMNode $candidate)
    {
        return true;
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $languageCode = strtolower(substr(Config::get('letterpress.locale'), 0, 2));

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
