<?php

namespace EFrane\Letterpress\Markup;

use Illuminate\Support\Str;

/**
 * Class DOMNodeType.
 *
 * @method element
 * @method attribute
 * @method text
 * @method cdataSection
 * @method entityRef
 * @method entity
 * @method pi
 * @method comment
 * @method document
 * @method documentType
 * @method documentFrag
 * @method notation
 * @method isElement(\DOMNode $node)
 * @method isAttribute(\DOMNode $node)
 * @method isText(\DOMNode $node)
 * @method isCdataSection(\DOMNode $node)
 * @method isEntityRef(\DOMNode $node)
 * @method isEntity(\DOMNode $node)
 * @method isPi(\DOMNode $node)
 * @method isComment(\DOMNode $node)
 * @method isDocument(\DOMNode $node)
 * @method isDocumentType(\DOMNode $node)
 * @method isDocumentFrag(\DOMNode $node)
 * @method isNotation(\DOMNode $node)
 */
class DOMNodeType
{
    protected $nodeTypes = [
        'XML_ELEMENT_NODE',
        'XML_ATTRIBUTE_NODE',
        'XML_TEXT_NODE',
        'XML_CDATA_SECTION_NODE',
        'XML_ENTITY_REF_NODE',
        'XML_ENTITY_NODE',
        'XML_PI_NODE',
        'XML_COMMENT_NODE',
        'XML_DOCUMENT_NODE',
        'XML_DOCUMENT_TYPE_NODE',
        'XML_DOCUMENT_FRAG_NODE',
        'XML_NOTATION_NODE',
    ];

    public static function __callStatic($name, $arguments)
    {
        $instance = new self();

        if (count($arguments) > 0) {
            $arguments = $arguments[0];
        }

        return $instance->$name($arguments);
    }

    public function getAvailableNodeTypes()
    {
        return collect($this->nodeTypes)->map(function ($nodeType) {
            return $this->getHumanReadableTypeName($nodeType);
        })->toArray();
    }

    public function getHumanReadableTypeName($nodeType)
    {
        return Str::camel(Str::lower(preg_replace('/XML_([A-Z_]+)_NODE/', '$1', $nodeType)));
    }

    public function __get($name)
    {
        return constant($this->getConstantName($name));
    }

    public function getConstantName($humanReadableName)
    {
        return 'XML_' . Str::upper(Str::snake($humanReadableName)) . '_NODE';
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 2) === 'is') {
            $name = substr($name, 2);

            if (count($arguments) !== 1 || !is_a($arguments[0], \DOMNode::class))
                return false;

            try {
                return constant($this->getConstantName($name)) === $arguments[0]->nodeType;
            } catch (\Exception $e) {
                return false;
            }
        }

        return constant($this->getConstantName($name));
    }
}

if (!function_exists('dom_node_type')) {
    /**
     * @param string $name
     *
     * @return \EFrane\Letterpress\Markup\DOMNodeType|int
     */
    function dom_node_type($name = '')
    {
        $instance = new DOMNodeType();

        return ($name === '') ? $instance : $instance->{$name};
    }
}
