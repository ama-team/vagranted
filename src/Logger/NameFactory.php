<?php

namespace AmaTeam\Vagranted\Logger;

/**
 * @author Etki <etki@etki.me>
 */
class NameFactory
{
    /**
     * @var string[]
     */
    private $namespaces = [];
    /**
     * @var string
     */
    private $prefix;

    /**
     * @param string[] $namespaces
     * @param string $prefix
     */
    public function __construct(array $namespaces = [], $prefix = '')
    {
        $this->prefix = $prefix;
        $this->namespaces = $namespaces;
    }

    /**
     * Analyzes class name and produces logger name.
     *
     * @param string $class
     * @return string
     */
    public function convert($class)
    {
        foreach ($this->namespaces as $prefix) {
            if (strpos($class, $prefix) === 0) {
                $class = substr($class, strlen($prefix));
                break;
            }
        }
        trim($class, '\\');
        $patterns = [
            '~\\\\+(\w)~',
            '~([a-z])([A-Z])~'
        ];
        $replacements = [
            '.$1',
            '$1_$2',
        ];
        $name = preg_replace($patterns, $replacements, $class);
        return $this->prefix . strtolower($name);
    }
}
