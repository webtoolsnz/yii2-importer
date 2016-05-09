<?php

namespace  webtoolsnz\importer;

class BaseImportModel extends \yii\db\ActiveRecord
{
    const EVENT_BEFORE_PROCESS_ROW = 'before_process_row';

    const STATUS_SUCCESS = 10;
    const STATUS_ERROR = 20;
}