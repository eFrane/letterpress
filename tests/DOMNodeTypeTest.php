<?php

use EFrane\Letterpress\Markup\DOMNodeType;

class DOMNodeTypeTest extends TestCase
{
    /**
     * @var DOMNodeType
     */
    protected $instance = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->instance = new DOMNodeType();
    }

    public function testGetAvailableNodeTypes()
    {
        $actual = $this->instance->getAvailableNodeTypes();

        $expected = $this->humanNames();

        $this->assertEquals($expected, $actual);
    }

    public function humanNames()
    {
        return [
            'element',
            'attribute',
            'text',
            'cdataSection',
            'entityRef',
            'entity',
            'pi',
            'comment',
            'document',
            'documentType',
            'documentFrag',
            'notation',
        ];
    }

    /**
     * @dataProvider data
     */
    public function testGetHumanReadableName($actual, $expected)
    {
        $this->assertEquals($expected, $this->instance->getHumanReadableTypeName($actual));
    }

    /**
     * @dataProvider data
     */
    public function testGetConstantName($expected, $actual)
    {
        $this->assertEquals($expected, $this->instance->getConstantName($actual));
    }

    /**
     * @dataProvider data
     */
    public function testMagicGet($expected, $actual)
    {
        $this->assertEquals(constant($expected), $this->instance->$actual);
    }

    /**
     * @dataProvider data
     */
    public function testMagicCall($expected, $actual)
    {
        $this->assertEquals(constant($expected), $this->instance->$actual());
    }

    /**
     * @dataProvider data
     */
    public function testStaticCall($expected, $actual)
    {
        $this->assertEquals(constant($expected), DOMNodeType::$actual());
    }

    /**
     * @dataProvider givenNodeData
     */
    public function testCallWithGivenNode($name, $node, $expected)
    {
        $func = 'is' . ucfirst($name);

        $this->assertEquals($expected, DOMNodeType::$func($node));
        $this->assertEquals($expected, $this->instance->$func($node));
    }

    public function givenNodeData()
    {
        return [
            ['element', new DOMElement('p'), true],
            ['element', new DOMEntity(), false],
            ['text', new DOMText(), true],
        ];
    }

    public function data()
    {
        $data = array_combine($this->constantNames(), $this->humanNames());

        return collect($data)->map(function ($value, $key) {
            return [$key, $value];
        })->toArray();
    }

    public function constantNames()
    {
        return [
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
    }
}
