<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\fields;

use mckenzieclark\fuzzy\Fuzzy;
use mckenzieclark\fuzzy\assetbundles\fuzzyeventfield\FuzzyEventFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class FuzzyEvent extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $defaultDay = 1;
    public $defaultMonth = 1;
    public $link;

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('fuzzy', 'Fuzzy Event');
    }

    // Public Methods
    // =========================================================================
    //

    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
      if(is_array($value)) return $value;
      return json_decode($value);
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
      return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public static function hasContentColumn(): bool
    {
      return false;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'fuzzy/_components/fields/FuzzyEvent_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(FuzzyEventFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'settings' => Fuzzy::getInstance()->getSettings(),
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').FuzzyFuzzyEvent(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'fuzzy/_components/fields/FuzzyEvent_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'settings' => Fuzzy::getInstance()->getSettings(),
                'namespacedId' => $namespacedId,
            ]
        );
    }

    public function afterElementSave($element, $isNew)
    {
      //var_dump($element->fuzzyEvent);
      //exit;
      //var_dump($isNew);exit;
    }
}
