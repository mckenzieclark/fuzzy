<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\assetbundles\fuzzy;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class FuzzyAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@mckenzieclark/fuzzy/assetbundles/fuzzy/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/fuzzy.js',
        ];

        $this->css = [
            'css/fuzzy.css',
        ];

        parent::init();
    }
}
