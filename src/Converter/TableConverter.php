<?php

namespace Mcgrogan91\HTMLToMarkdown\Converter;

use League\HTMLToMarkdown\ElementInterface;
use League\HTMLToMarkdown\Converter\ConverterInterface;

class TableConverter implements ConverterInterface
{
    /**
     *
     * TODO: Make this less bad
     *
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        $table = "";
        $children = $element->getChildren();
        if (count($children) > 0) {
            $first = $children[0];
            if ($first->getTagName() !== "tr") {
                $table = $this->convert($first);
            } else {
                $table = "|";
                $cut = "|";
                foreach ($first->getChildren() as $headerData) {
                    $table .= " " . $headerData->getValue() . " |";
                    $cut .= " --- |";
                }
                $table .= "
$cut";
                for ($count = 1; $count < count($children); $count++) {
                    $row = $children[$count];
                    $table .= "
";
                    $table .= "|";
                    foreach ($row->getChildren() as $data) {
                        $table .= " " . $data->getValue() . " |";
                    }
                }

            }
        }

        return $table;
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return array('table');
    }
}