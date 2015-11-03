<?php

namespace Mcgrogan91\HTMLToMarkdown\Converter;

use League\HTMLToMarkdown\ElementInterface;
use League\HTMLToMarkdown\ConverterInterface;

class TableConverter implements ConverterInterface
{
    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        var_dump($element);die();
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return array('table');
    }
}