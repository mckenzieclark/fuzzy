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
use craft\elements\Entry;
use craft\web\View;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterComponentTypesEvent;
use craft\controllers\EntriesController;
use craft\web\UrlManager;
use craft\web\Application;

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
          EntriesController::class,
          EntriesController::EVENT_BEFORE_ACTION,
          function(Event $event) {

            $data = ['related' => []];

            $request = Craft::$app->urlManager->parseRequest(Craft::$app->request);

            if(!in_array($request[1]['section'], ['people'])) return $event;

            $entryId = $request[1]['entryId'];
            $fields = [22];

            foreach($fields as $key => $id) {
              $field = Craft::$app->fields->getFieldById($id);
              $handle = $field->handle;
            }

            $entry = Entry::find()->id($entryId)->one();
            $events = $entry->events->all();
            $related = Entry::find()->section('events')->relatedTo([
              'targetElement' => $entry,
              'field' => $handle
            ])->all();

            foreach($related as $idx => $entry) {
              $data['related'][] = $entry->id;
            }

            $merged = array_merge($events, $related);

            $sorted = Fuzzy::getInstance()->fuzzyDate->sort($merged, 'fuzzyDate asc');

            $data['entries'] = $sorted;
            $data['authorId'] = $entry->author->id;

            $json = json_encode($data);

            //var_dump($merged);exit;

            $config = [
              'id' => 'fuzzy-field-events',
              'name' => 'fuzzyEvents',
              'elementType' => 'Entry',
              'elements' => $sorted
            ];

            $view = Craft::$app->view->renderTemplate('fuzzy/_includes/forms/elementSelect.twig', $config, View::TEMPLATE_MODE_CP);
            $stripped = str_replace(["\r", "\n"], '', $view);

            $js = "document.getElementById('fields-events').insertAdjacentHTML('afterBegin', '$stripped');";
            Craft::$app->view->registerJs($js);

            //Craft::$app->view->registerJsFile('/plugins/fuzzy/src/assetbundles/fuzzy/dist/js/FuzzyElementSelectInput.js');

            //Craft::$app->view->registerJs('new Craft.FuzzyElementSelectInput({"id":"fuzzy-field-events","name":"fuzzyEvents","elementType":"craft\\\elements\\\Entry","sources":null,"criteria":null,"allowSelfRelations":false,"sourceElementId":null,"disabledElementIds":null,"viewMode":"list","limit":null,"showSiteMenu":false,"modalStorageKey":null,"fieldId":null,"sortable":false,"modalSettings":[]});');
          }
        );

        Craft::$app->on(Application::EVENT_INIT, function() {
        });

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

        /*
        Event::on(Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = FuzzyEvent::class;
            }
        );
         */

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
