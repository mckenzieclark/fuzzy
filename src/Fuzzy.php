<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy;

use mckenzieclark\fuzzy\services\FuzzyService;
use mckenzieclark\fuzzy\services\FuzzyDate as FuzzyDateService;
use mckenzieclark\fuzzy\services\FuzzyEvent as FuzzyEventService;
use mckenzieclark\fuzzy\variables\FuzzyVariable;
use mckenzieclark\fuzzy\models\Settings;
use mckenzieclark\fuzzy\fields\FuzzyDate as FuzzyDateField;
use mckenzieclark\fuzzy\fields\FuzzyEvent as FuzzyEventField;
use mckenzieclark\fuzzy\elements\FuzzyEvent as FuzzyEvent;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class Fuzzy
 *
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 *
 * @property  FuzzyDateService $fuzzyDate
 * @property  FuzzyEventService $fuzzyEvent
 */
class Fuzzy extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Fuzzy
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = FuzzyDateField::class;
                $event->types[] = FuzzyEventField::class;
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('fuzzy', FuzzyVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

      Event::on(Elements::class,
          Elements::EVENT_REGISTER_ELEMENT_TYPES,
          function(RegisterComponentTypesEvent $event) {
              $event->types[] = FuzzyEvent::class;
          }
      );

        Craft::info(
            Craft::t(
                'fuzzy',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'fuzzy/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
