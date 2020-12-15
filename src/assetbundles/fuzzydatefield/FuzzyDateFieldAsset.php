<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\assetbundles\fuzzydatefield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class FuzzyDateFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@mckenzieclark/fuzzy/assetbundles/fuzzydatefield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/FuzzyDate.js',
        ];

        $this->css = [
            'css/FuzzyDate.css',
        ];

        parent::init();
    }
}
