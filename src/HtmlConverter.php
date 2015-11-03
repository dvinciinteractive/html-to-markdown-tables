<?php

namespace Mcgrogan91\HTMLToMarkdown;

use League\HTMLToMarkdown\ElementInterface;
use Mcgrogan91\HTMLToMarkdown\Converter\TableConverter;
use Mcgrogan91\HTMLToMarkdown\Converter\TrConverter;

class HtmlConverter extends \League\HTMLToMarkdown\HtmlConverter
{
    /**
     * @var Environment
     */
    protected $environment;

    /**
     * Constructor
     *
     * @param array $options Configuration options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->environment->addConverter(new TableConverter());
        //$this->environment->addConverter(new TrConverter());
    }

    /**
     * Convert Children
     *
     * Recursive function to drill into the DOM and convert each node into Markdown from the inside out.
     *
     * Finds children of each node and convert those to #text nodes containing their Markdown equivalent,
     * starting with the innermost element and working up to the outermost element.
     *
     * @param ElementInterface $element
     */
    private function convertChildren(ElementInterface $element)
    {
        // Don't convert HTML code inside <code> and <pre> blocks to Markdown - that should stay as HTML
        if ($element->isDescendantOf(array('pre', 'code'))) {
            return;
        }

        // If the node has children, convert those to Markdown first
        if ($element->getTagName() !== "table"   && $element->hasChildren()) {
            foreach ($element->getChildren() as $child) {
                $this->convertChildren($child);
            }
        }

        // Now that child nodes have been converted, convert the original node
        $markdown = $this->convertToMarkdown($element);

        // Create a DOM text node containing the Markdown equivalent of the original node

        // Replace the old $node e.g. '<h3>Title</h3>' with the new $markdown_node e.g. '### Title'
        $element->setFinalMarkdown($markdown);
    }

}