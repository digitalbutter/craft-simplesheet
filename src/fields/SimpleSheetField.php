<?php
/**
 * SimpleSheet plugin for Craft CMS 3.x
 *
 * Provides an additional Spreadsheet Field Type for Craft CMS.
 *
 * @link      https://github.com/digitalbutter/craft-simplesheet
 * @copyright Copyright (c) 2020 Daniel Jackson
 */

namespace dgjackson\simplesheet\fields;

use dgjackson\simplesheet\SimpleSheet;
use dgjackson\simplesheet\models\Sheet;
use dgjackson\simplesheet\assetbundles\simplesheet\SimpleSheetFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use craft\web\View;
use craft\helpers\Json;

use yii\db\Schema;

/**
 * SimpleSheet Field
 *
 * https://craftcms.com/docs/plugins/field-types
 *
 * @author    Daniel Jackson
 * @package   SimpleSheet
 * @since     1
 */
class SimpleSheetField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var mixed
     */
    public $data = null;

    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('simplesheet', 'SimpleSheet');
    }

    public static function hasContentColumn(): bool
    {
        return true;
    }

    // Public Methods
    // =========================================================================

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    public function getAttributeLabel($attribute): string
    {
        $label = parent::getAttributeLabel($attribute);

        return Craft::t('simplesheet', $label);
    }

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    /**
     * Normalizes the field’s value for use (Sheet).
     * 
     * @param mixed                 $value   The raw field value
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     *
     * @return mixed The prepared field value
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if (is_string($value)) {
            $value = Json::decodeIfJson($value);
        }

        if ($value instanceof Sheet) {
            $sheet = $value;
        } else if (is_array($value)) {
            $sheet = new Sheet($value);
        } else {
            $sheet = new Sheet([
                'data' => null,
            ]);
        }

        $sheet->fieldId = $this->id;

        if ($element) {
            $sheet->ownerId = $element->id;
            $sheet->ownerSiteId = $element->siteId;

            $handle = $this->handle;
            $element->setFieldValue($handle, $sheet);
        }

        return $sheet;
    }

    /**
     * Prepares the field’s value to be stored somewhere, like the content table or JSON-encoded in an entry revision table.
     *
     * Data types that are JSON-encodable are safe (arrays, integers, strings, booleans, etc).
     * Whatever this returns should be something [[normalizeValue()]] can handle.
     *
     * @param mixed $value The raw field value
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     * @return mixed The serialized field value
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * Returns the component’s settings HTML.
     * 
     * @return string|null
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'simplesheet/_components/fields/SimpleSheet_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * Returns the field’s input HTML.
     *
     * @param mixed                 $value           The field’s value. This will either be the [[normalizeValue() normalized value]],
     *                                               raw POST data (i.e. if there was a validation error), or null
     * @param ElementInterface|null $element         The element the field is associated with, if there is one
     *
     * @return string The input HTML.
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register HoT assets from CDN
        Craft::$app->getView()->registerScript(
            '',
            View::POS_END,
            ['src' => 'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js'],
            md5('https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js'),
        );

        Craft::$app->getView()->registerCssFile(
            'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css'
          );

        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(SimpleSheetFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').SimpleSheet(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'simplesheet/_components/fields/SimpleSheet_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
