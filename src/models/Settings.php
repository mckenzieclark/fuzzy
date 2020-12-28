<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\models;

use mckenzieclark\fuzzy\Fuzzy;

use Craft;
use craft\base\Model;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $defaultDay = 1;
    public $defaultMonth = 1;
    public $eventTypes = [
      'army' => [
        'name' => 'Army Service',
        'endDate' => true,
        'location' => true
      ],
      'wedding' => [
        'name' => 'Wedding',
        'related' => true,
        'location' => true
      ],
      'divorce' => [
        'name' => 'Divorce',
        'related' => true
      ]
    ];

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['defaultDay', 'number'],
            ['defaultDay', 'default', 'value' => 1],
            ['defaultMonth', 'number'],
            ['defaultMonth', 'default', 'value' => 1],
        ];
    }
}
