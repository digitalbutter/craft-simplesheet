<?php

namespace dgjackson\simplesheet\web\twig\variables;

use dgjackson\simplesheet\SimpleSheet;
use Exception;

class SimplesheetVariable
{
  /**
   * Returns the markup for a simplesheet embedded element
   * @param $options - Currently only accepts id or NULL
   * 
   * @return string|void
   * @throws \yii\base\InvalidConfigException
   */
  public function getEmbed($options = [])
  {
    return SimpleSheet::getInstance()->embed->embed($options);
  }
}
