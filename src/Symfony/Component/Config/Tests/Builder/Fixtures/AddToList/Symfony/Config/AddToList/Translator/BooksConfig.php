<?php

namespace Symfony\Config\AddToList\Translator;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Books'.\DIRECTORY_SEPARATOR.'PageConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class BooksConfig 
{
    private $page;
    private $_usedProperties = [];

    /**
     * @example "page 1"
     * @default {"number":1,"content":""}
     */
    public function page(array $value = []): \Symfony\Config\AddToList\Translator\Books\PageConfig
    {
        $this->_usedProperties['page'] = true;

        return $this->page[] = new \Symfony\Config\AddToList\Translator\Books\PageConfig($value);
    }

    /**
     * @param array{ // Deprecated: The child node "books" at path "add_to_list.translator.books" is deprecated. // looks for translation in old fashion way
     *     page?: list<array{
     *         number?: int<min, max>,
     *         content?: scalar|null,
     *     }>,
     * } $config
     */
    public function __construct(array $config = [])
    {
        if (array_key_exists('page', $config)) {
            $this->_usedProperties['page'] = true;
            $this->page = array_map(fn ($v) => new \Symfony\Config\AddToList\Translator\Books\PageConfig($v), $config['page']);
            unset($config['page']);
        }

        if ($config) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($config)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['page'])) {
            $output['page'] = array_map(fn ($v) => $v->toArray(), $this->page);
        }

        return $output;
    }

}
