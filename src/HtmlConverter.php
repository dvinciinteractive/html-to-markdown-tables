<?php

namespace Mcgrogan91\HTMLToMarkdown;

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
        $this->environment->addConverter(new TrConverter());
    }

}