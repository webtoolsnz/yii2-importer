<?php

namespace  webtoolsnz\importer;

/**
 * Class BaseImportModel
 * @package webtoolsnz\importer
 */
class BaseImportModel extends \yii\db\ActiveRecord
{
    /**
     * This event is triggered before processing each row.
     */
    const EVENT_BEFORE_PROCESS_ROW = 'before_process_row';
    const EVENT_AFTER_PROCESS_ROW = 'after_process_row';

    const STATUS_SUCCESS = 10;
    const STATUS_ERROR = 20;

    /**
     * Whether or not the import model should be deleted when the import is complete.
     * @var bool
     */
    public $deleteAfterImport = false;

    /**
     * If you want to ignore the case of header names, enable this.
     * @var bool
     */
    public $headerIgnoreCase = false;

    /**
     * If you have extra columns on your import table that need to be ignored by the validation, you can add them here.
     *
     * @return array
     */
    public function getIgnoredAttributes()
    {
        return  ['id', 'import_id', 'import_status_id', 'import_error'];
    }

    /**
     * @param $import_id
     * @return array
     */
    public function getImportQueries($import_id)
    {
        return [];
    }

    /**
     * @return string
     */
    public function generateErrorSummary()
    {
        $errors = array_values($this->getFirstErrors());
        $unique = array_unique($errors);
        return implode(', ', $unique);
    }
}