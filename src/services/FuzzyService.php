<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\services;

use mckenzieclark\fuzzy\Fuzzy;

use Craft;
use craft\base\Component;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class FuzzyService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Fuzzy::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}

