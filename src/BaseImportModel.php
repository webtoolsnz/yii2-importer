<?php

namespace  webtoolsnz\importer;

/**
 * Class BaseImportModel
 * @package webtoolsnz\importer
 */
class BaseImportModel extends \yii\db\ActiveRecord
{
    /**
     * Whether or not the import model should be deleted when the import is complete.
     * @var bool
     */
    public $deleteAfterImport = true;

    /**
     * This event is triggered before processing each row.
     */
    const EVENT_BEFORE_PROCESS_ROW = 'before_process_row';

    const STATUS_SUCCESS = 10;
    const STATUS_ERROR = 20;

    public function getImportQueries($import_id)
    {
        return [];
    }
}