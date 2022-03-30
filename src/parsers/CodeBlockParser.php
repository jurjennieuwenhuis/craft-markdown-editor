<?php
declare(strict_types=1);

namespace juni\markdowneditor\parsers;

class CodeBlockParser extends BaseParser
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * @var string
     */
    protected static $codeBlockSnippet;

    /**
     * Parses code blocks within html content and returns a new code block markup based on settings
     *
     * @param string $source
     * @param array $options
     *
     * @return string
     */
    public function parse($source, array $options = [])
    {
        $codeBlockSnippet = null;

        extract($options, EXTR_OVERWRITE);

        if (empty($codeBlockSnippet)) {
            return $source;
        }

        self::$codeBlockSnippet = $codeBlockSnippet;

        return $this->parseCodeBlocks($source);
    }

    /**
     * Parses code block within html source
     *
     * @param string $source
     *
     * @return string
     */
    protected function parseCodeBlocks($source)
    {
        if (!$this->canBeSafelyParsed($source) || stripos($source, '<pre>') === false) { return $source; }

        $pattern = '/<pre(.?)<code class\="([a-z\-\_]+)">(.*?)<\/code>(.?)<\/pre>/ism';
        $source  = preg_replace_callback($pattern, [$this, 'handleBlockMatch'], $source);

        return $source;
    }

    /**
     * Returns a new code block based on matched content and settings
     *
     * @param array $matches
     *
     * @return string
     */
    protected function handleBlockMatch(array $matches = [])
    {
        $lang   = str_replace('language-', '', $matches[2]);
        $code   = $matches[3];
        $source = str_replace('{languageClass}', $lang, self::$codeBlockSnippet);
        $source = str_replace('{sourceCode}', $code, $source);

        return $source;
    }
}
