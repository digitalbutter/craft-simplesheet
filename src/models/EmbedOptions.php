<?php
/**
 * Spreadsheets for Craft CMS
 * 
 * @link      https://github.com/dgjackson
 * @copyright Copyright (c) 2020 Daniel Jackson
 */

namespace dgjackson\simplesheet\models;

use craft\helpers\StringHelper;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class EmbedOptions
 *
 * @author Daniel Jackson
 * @package dgjackson\simplesheet\models
 */
class EmbedOptions
{

  // Properties
  // =========================================================================

  /** @var string The ID of the spreadsheet container (unique ID will be generated if null) */
  public $id;

  /** @var string The width of the spreadsheet container */
  public $width = '100%';

  /** @var string The height of the spreadsheet container */
  public $height = '500';

  /** @var string The spreadsheet data */
  public $data = '';

  /** @var array Options to be passed to the JS spreadsheet constructor */
  public $options = [];

  // Constructor
  // =========================================================================

  public function __construct($config = [])
  {
    if (!empty($config)) {
      Yii::configure($this, $config);
    }

    if (!$this->id) {
      $this->id = StringHelper::appendUniqueIdentifier('simplesheet');
    } 
  }
}
