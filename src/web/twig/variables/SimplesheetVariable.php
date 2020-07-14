<?php
/**
 * SimpleSheet plugin for Craft CMS 3.x
 *
 * Provides an additional Spreadsheet Field Type for Craft CMS.
 *
 * @link      https://github.com/digitalbutter/craft-simplesheet
 * @copyright Copyright (c) 2020 Daniel Jackson
 */

namespace dgjackson\simplesheet\web\twig\variables;

use dgjackson\simplesheet\SimpleSheet;
use Exception;

class SimplesheetVariable
{
  /**
   * Returns the markup for a simplesheet embedded element
   * @param $options - Currently only accepts an id
   * 
   * @return string|void
   * @throws \yii\base\InvalidConfigException
   */
  public function getEmbed($options = [])
  {
    return SimpleSheet::getInstance()->embed->embed($options);
  }
}
