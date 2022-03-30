<?php
declare(strict_types=1);

namespace juni\markdowneditor\plugin;

use juni\markdowneditor\services\MarkdownParser;

/**
 * Trait Services
 *
 * @property MarkdownParser $markdownParser the markdown parser service
 */
trait Services
{
    /**
     * Returns the markdown parser service
     *
     * @return MarkdownParser The markdown parser service
     */
    public function getMarkdownParser(): MarkdownParser
    {
        return $this->get('markdownparser');
    }

    /**
     * Sets the components of the commerce plugin
     */
    private function setPluginComponents()
    {
        $this->setComponents([
            'markdownparser' => [
                'class' => MarkdownParser::class,
            ]
        ]);
    }
}
