<?php

namespace webtoolsnz\importer;

class ImportEvent extends \yii\base\Event
{
    /**
     * @var \webtoolsnz\importer\models\Import
     */
    public $import;

    /**
     * @var array
     */
    public $row;
}