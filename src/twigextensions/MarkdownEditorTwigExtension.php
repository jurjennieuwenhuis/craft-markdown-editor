<?php
/**
 * themehelper plugin for Craft CMS 3.x
 *
 * Craft CMS plugin containing twig functions and filters.
 *
 * @link      http://www.kasanova.nl/
 * @copyright Copyright (c) 2018 Jurjen Nieuwenhuis
 */

namespace juni\markdowneditor\twigextensions;

use craft\helpers\Template as TemplateHelper;

use juni\markdowneditor\services\MarkdownParser;
use juni\markdowneditor\MarkdownEditor as Plugin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Jurjen Nieuwenhuis
 * @package   Themehelper
 * @since     1.0.0
 */
class MarkdownEditorTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'MarkdownEditor';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('parsedown', [$this, 'parsedown'], ['is_safe' => ['html']]),
            new TwigFilter('typogrify', [$this, 'typogrify']),
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('parsedown', [$this, 'parsedown']),
            new TwigFunction('typogrify', [$this, 'typogrify']),
        ];
    }

    public function parsedown($var = '', array $options = [])
    {
        $parser = Plugin::getInstance()->getMarkdownParser();
        return $parser->parse($var, $options);
    }

    public function typogrify($var = '')
    {
        /** @var MarkdownParser $service */
        $service = Plugin::getInstance()->getMarkdown();

        $var = $service->addTypographyStyles($var);

        return TemplateHelper::raw($var);
    }
}
