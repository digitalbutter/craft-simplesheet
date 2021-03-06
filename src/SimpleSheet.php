<?php
/**
 * SimpleSheet plugin for Craft CMS 3.x
 *
 * Provides an additional Spreadsheet Field Type for Craft CMS.
 *
 * @link      https://github.com/digitalbutter
 * @copyright Copyright (c) 2020 Daniel Jackson
 */

namespace dgjackson\simplesheet;

use dgjackson\simplesheet\fields\SimpleSheetField as SimpleSheetField;
use dgjackson\simplesheet\web\twig\variables\SimplesheetVariable;
use dgjackson\simplesheet\services\EmbedService;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\web\View;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Daniel Jackson
 * @package   SimpleSheet
 * @since     1
 *
 */
class SimpleSheet extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * SimpleSheet::$plugin
     *
     * @var SimpleSheet
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    public $schemaVersion = '1';

    public $hasCpSettings = false;

    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * Instantiates plugin and creates a static property to this class for global
     * access. Performs one-time initialization for hooks and events.
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'embed' => EmbedService::class,
        ]);

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = SimpleSheetField::class;
            }
        );

        // Register our class variable
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                $variable = $event->sender;
                $variable->set('simpleSheet', SimplesheetVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'simplesheet',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function getSettings()
    {
        return parent::getSettings();
    }
}
