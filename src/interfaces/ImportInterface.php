<?php

namespace webtoolsnz\importer\interfaces;

use yii\db\ActiveRecordInterface;

/**
 * Interface ImportInterface
 * @package common\interfaces
 *
 * @property integer $import_id
 * @property integer $import_status_id
 * @property string $import_error
 * @property \webtoolsnz\importer\models\Import $import
 */
interface ImportInterface extends ActiveRecordInterface
{
    /**
     * @return array
     */
    public function getColumnMap();

    /**
     * @return mixed
     */

    /**
     * @param $import_id
     * @return \webtoolsnz\importer\ImportQuery[]
     */
    public function getImportQueries($import_id);
}