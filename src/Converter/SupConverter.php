<?php
namespace Mcgrogan91\HTMLToMarkdown\Converter;


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
        return "<sup>" . $element->getValue() . "</sup>";
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return ['sup'];
    }
}