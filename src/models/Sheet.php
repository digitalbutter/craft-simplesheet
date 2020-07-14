<?php

namespace dgjackson\simplesheet\models;

use craft\base\Model;
use craft\helpers\Json;
use dgjackson\simplesheet\SimpleSheet;

class Sheet extends Model
{

    // Public Properties
    // =========================================================================

    public $id;

    public $ownerId;

    public $ownerSiteId;

    public $fieldId;

    public $data = null;

    // Public Methods
    // =========================================================================

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function embed($options = [])
    {
        $options = $this->_getSheetOptions($options);

        return SimpleSheet::getInstance()->embed->embed($options);
    }

    // Private Methods
    // =========================================================================

    private function _getSheetOptions($options)
    {
        return array_merge($options, [
            'data' => $this->data,
        ]);
    }
}
