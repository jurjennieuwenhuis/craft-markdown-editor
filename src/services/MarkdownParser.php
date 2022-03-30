<?php
declare(strict_types=1);

namespace juni\markdowneditor\services;

use craft\helpers\Template as TemplateHelper;
use juni\markdowneditor\parsers\CodeBlockParser;
use juni\markdowneditor\parsers\MarkdownParser as Parser;
use PHP_Typography\PHP_Typography;
use PHP_Typography\Settings;
use yii\base\Component;

final class MarkdownParser extends Component
{
    /**
     * @param $source
     * @param array $options
     * @return \Twig\Markup
     */
    public function parse($source, array $options = [])
    {
        $codeBlockSnippet    = null;
        $addTypographyStyles = true;
        $options = array_merge([
            'codeBlockSnippet' => '<pre><code data-language="language-{languageClass}" class="language-{languageClass}">{sourceCode}</code></pre>',
        ], $options);

        extract($options, EXTR_OVERWRITE);

        $source = $this->parseMarkdown($source);

        $source = $this->parseCodeBlocks($source, compact('codeBlockSnippet'));

        if ($addTypographyStyles) {
            $source = $this->addTypographyStyles($source, $options);
        }

        return TemplateHelper::raw($source);
    }

    /**
     * @param string $source
     * @param array  $options
     *
     * @return string
     */
    public function parseMarkdown($source, array $options = array())
    {
        return Parser::instance()->parse($source, $options);
    }

    /**
     * @param string $source
     * @param array  $options
     *
     * @return string
     */
    public function parseMarkdownInline($source, array $options = array())
    {
        return Parser::instance()->parseInline($source, $options);
    }

    /**
     * @param string $source
     * @param array  $options
     *
     * @return string
     */
    public function parseCodeBlocks($source, array $options = array())
    {
        return CodeBlockParser::instance()->parse($source, $options);
    }

    /**
     * @param string $source
     *
     * @return string
     */
    public function addTypographyStyles($source)
    {
        try {
            $settings = new Settings();
            $settings->set_hyphenation( true );
            $settings->set_hyphenation_language( 'en-US' );

            $typo = new PHP_Typography();

            $source = $typo->process( $source, $settings );

            return $source;
        } catch (\Exception $e) {
            return $source;
        }
    }
}
