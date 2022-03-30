<?php
/**
 * Markdown Editor plugin for Craft CMS 3.x
 *
 * @link      https://www.mitchartemis.dev
 * @copyright Copyright (c) 2019 Mitch Stanley
 */
namespace juni\markdowneditor\fields;

use Craft;
use craft\base\ElementInterface;
use craft\fields\PlainText;
use craft\helpers\Html;
use juni\markdowneditor\assets\field\FieldAsset;

/**
 * Markdown Editor field type
 */
class MarkdownEditorField extends PlainText
{
    // Properties
    // =========================================================================
    public $mode = 'plain';
    public $modeOverride;
    public $initJs;
    private $_modes;

    // Static Methods
    // =========================================================================
    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return 'Markdown Editor';
    }

    // Public Methods
    // =========================================================================
    public function __construct(array $config = [])
    {
        $this->_modes = [
            'plain' => Craft::t('site', 'Plain Text'),
        ];

        parent::__construct($config);
    }

    public function rules()
    {
        $rules = array_merge(parent::rules(), [
            [['mode'], 'string'],
            [['mode'], 'default', 'value' => 'plain'],
        ]);
        return $rules;
    }

    /**
     * @param mixed $value
     * @param ElementInterface|null $element
     *
     * @return string
     * @throws \Twig\Error\LoaderError | \Twig\Error\RuntimeError | \Twig\Error\SyntaxError | \yii\base\Exception
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $view = Craft::$app->getView();
        $view->registerAssetBundle(FieldAsset::class);

        $mode = $this->_getMode($element);

        $js = $this->initJs ?? <<<JS
Array.prototype.forEach.call(document.getElementsByClassName('craft-easymde'), function(element) {
    let easyMDE = new EasyMDE({element: element, forceSync: true});
});
JS;


        // Get our id and namespace
        $id = Html::id($this->handle);
        $nsId = Craft::$app->getView()->namespaceInputId($id);

        //$js = str_replace('__EDITOR__', $nsId, $js);
        $view->registerJs($js);

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'markdown-editor/_components/fields/_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $nsId,
                'mode' => $mode,
            ]
        );
    }

    // Private Methods
    // =========================================================================
    /**
     * @param ElementInterface|null $element
     * @return string
     * @throws \yii\base\Exception
     */
    private function _getMode(ElementInterface $element = null): string
    {
        // Return early if there is no override set
        if (empty($this->modeOverride)) {
            return $this->mode;
        }

        $view = Craft::$app->getView();
        $oldTemplateMode = $view->getTemplateMode();
        $view->setTemplateMode($view::TEMPLATE_MODE_SITE);

        try {
            $modeOverride = $view->renderString($this->modeOverride, ['element' => $element]);
            $modeOverride = trim($modeOverride);
        } catch (\Exception $e) {
            Craft::error('Unable to render mode override template. ' . $e->getMessage(), __METHOD__);
            $view->setTemplateMode($oldTemplateMode);
            return $this->mode;
        }

        $view->setTemplateMode($oldTemplateMode);

        // Don’t override if we got an empty string
        if ($modeOverride === '') {
            return $this->mode;
        }

        // Don’t override if we got an invalid value
        if (!array_key_exists($modeOverride, $this->_modes)) {
            Craft::error('Invalid value for mode override: '.$modeOverride, __METHOD__);
            return $this->mode;
        }

        return $modeOverride;
    }
}
