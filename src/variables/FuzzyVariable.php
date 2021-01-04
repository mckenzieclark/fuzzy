<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\variables;

use mckenzieclark\fuzzy\Fuzzy;
use DateTime;

use Craft;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class FuzzyVariable
{
    public function __construct()
    {
    }

    // Public Methods
    // =========================================================================

    /**
     * @param $date
     * @param $format
     * @param false $prefix
     * @return string
     */
    public function date($date, $format, $prefix = false)
    {
      return Fuzzy::getInstance()->fuzzyDate->date($date, $format, $prefix);
    }

    public function timestamp($date)
    {
      return Fuzzy::getInstance()->fuzzyDate->timestamp($date);
    }

    public function diff($date1, $date2, $options = null)
    {
      return Fuzzy::getInstance()->fuzzyDate->diff($date1, $date2, $options);
    }

    public function ago($date) 
    {
      return Fuzzy::getInstance()->fuzzyDate->ago($date);
    }
}
