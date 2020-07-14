<?php
/**
 * Spreadsheets for Craft CMS
 * 
 * @link      https://github.com/digitalbutter/craft-simplesheet
 * @copyright Copyright (c) 2020 Daniel Jackson
 */

namespace dgjackson\simplesheet\services;

use Craft;
use craft\base\Component;
use craft\helpers\Template;
use craft\helpers\Json;
use craft\web\View;

use dgjackson\simplesheet\models\EmbedOptions;
use dgjackson\simplesheet\SimpleSheet;

class EmbedService extends Component
{

  /**
   * Renders the SimpleSheet instance.
   * 
   * @param array $options
   * 
   * @return string|void
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function embed($options = [])
  {
    $options = new EmbedOptions($options);

    $settings = SimpleSheet::getInstance()->getSettings();

    return Template::raw($this->_embedSimplesheet($options, $settings));
  }

  // Private functions
  // =========================================================================

  /**
   * @param EmbedOptions  $options
   * 
   * @return string
   * @throws \Exception
   */
  private function _embedSimplesheet (EmbedOptions $options)
  {
    $view = Craft::$app->getView();
    $callbackName = 'init_' . $options->id;

    $data = Json::encode($options->data);
    
    $js = <<<JS
const {$options->id} = new Handsontable(document.getElementById('{$options->id}'), {
    data: {$data},
    startCols: 26,
    startRows: 100,
    readOnly: true,
    colHeaders: true,
    rowHeaders: true,
    columnSorting: {
      indicator: true,
    },
    licenseKey: 'non-commercial-and-evaluation',
  }
);
JS;

  $view->registerScript(
    '',
    View::POS_END,
    ['src' => 'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js'],
    md5('https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js'),
  );

  $view->registerCssFile(
    'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css'
  );

  $css = $this->_getCss($options);
  $view->registerJs($js, View::POS_END);
  $css && $view->registerCss($css);

  return '<div id="' . $options->id . '"></div>';

  }

  /**
   * Adds css properties to our container element so that the spreadsheet
   * renders correctly.
   * 
   * @param EmbedOptions $options
   */
  private function _getCss(EmbedOptions $options)
  {
    if ($options->width === null && $options->height === null)
      return null;

    $css = "#{$options->id} {";

    $css .= 'overflow: auto;';

    if ($options->width !== null)
      $css .= 'width:' . $options->width . ';';
    
    if ($options->height !== null)
      $css .= 'height:' . $options->height . ';';

    return $css . '}';
  }
}
