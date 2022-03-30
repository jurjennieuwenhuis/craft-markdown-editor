<?php
declare(strict_types=1);

namespace juni\markdowneditor\parsers;

abstract class BaseParser
{
    protected static $instance;

    public static function instance()
    {
        if (static::$instance === null) {
            $name = get_called_class();

            static::$instance = new $name;
        }

        return static::$instance;
    }

    /**
     * Reports whether the source string can be safely parsed
     *
     * @param string $source
     *
     * @return bool
     */
    public function canBeSafelyParsed($source)
    {
        if (empty($source))
        {
            return false;
        }

        return (is_string($source) || is_callable(array($source, '__toString')));
    }

    /**
     * Must be implemented by all extending parsers
     *
     * @param string $source
     * @param array $options
     *
     * @return mixed
     */
    abstract public function parse($source, array $options=array());
}
