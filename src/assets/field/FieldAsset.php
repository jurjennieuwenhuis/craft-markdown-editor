<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license MIT
 */

namespace juni\markdowneditor\assets\field;

use craft\ckeditor\assets\ckeditor\CkeditorAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Markdown Editor field asset bundle
 */
class FieldAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/dist';

    /**
     * @inheritdoc
     */
    public $depends = [
        CpAsset::class,
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'easymde.min.css',
        'overrides.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'easymde.min.js',
        'init.js',
    ];
}
