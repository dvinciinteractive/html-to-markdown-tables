<?php
namespace Dvinci\HTMLToMarkdown\Converter;


use League\HTMLToMarkdown\Converter\ConverterInterface;
use League\HTMLToMarkdown\ElementInterface;

class SupConverter implements ConverterInterface{

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        $tag = $element->getTagName();
        $value = trim($element->getValue());

        switch ($tag) {
            case 'sup':
                return "<sup>" . $value . "</sup>";
            case 'sub':
                return "<sub>" . $value . "</sub>";
            default:
                return $value;
        }
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return array('sup', 'sub');
    }
}
