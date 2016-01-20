<?php

namespace Dvinci\HTMLToMarkdown;

use League\HTMLToMarkdown\Element;
use League\HTMLToMarkdown\ElementInterface;
use Dvinci\HTMLToMarkdown\Converter\SupConverter;
use Dvinci\HTMLToMarkdown\Converter\TableConverter;

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
        $this->environment->addConverter(new SupConverter());
    }

    /**
     * Convert
     *
     * Loads HTML and passes to getMarkdown()
     *
     * @param $html
     *
     * @return string The Markdown version of the html
     */
    public function convert($html)
    {
        if (trim($html) === '') {
            return '';
        }

        $document = $this->createDOMDocument($html);

        // Work on the entire DOM tree (including head and body)
        if (!($root = $document->getElementsByTagName('html')->item(0))) {
            throw new \InvalidArgumentException('Invalid HTML was provided');
        }

        $rootElement = new Element($root);
        $this->convertChildren($rootElement);

        // Store the now-modified DOMDocument as a string
        $markdown = $document->saveHTML();

        $markdown = $this->sanitize($markdown);

        return $markdown;
    }


    /**
     * @param string $html
     *
     * @return \DOMDocument
     */
    private function createDOMDocument($html)
    {
        $document = new \DOMDocument();

        if ($this->getConfig()->getOption('suppress_errors')) {
            // Suppress conversion errors (from http://bit.ly/pCCRSX)
            libxml_use_internal_errors(true);
        }

        // Hack to load utf-8 HTML (from http://bit.ly/pVDyCt)
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        $document->encoding = 'UTF-8';

        if ($this->getConfig()->getOption('suppress_errors')) {
            libxml_clear_errors();
        }

        return $document;
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
        if ($element->getTagName() !== "table"  && $element->hasChildren()) {
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
