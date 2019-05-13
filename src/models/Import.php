<?php

namespace webtoolsnz\importer\models;

use webtoolsnz\importer\BaseImportModel;
use webtoolsnz\importer\interfaces\ImportInterface;
use webtoolsnz\importer\ImportEvent;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use League\Csv\Reader;
use yii\helpers\ArrayHelper;

/**
 * Class Import
 * @package webtoolsnz\importer\models
 */
class Import extends \webtoolsnz\importer\models\base\Import
{
    const SCENARIO_IMPORT_VALIDATE = 'import_validate';
    const SCENARIO_INSERT = 'insert';

    const STATUS_PENDING = 10;
    const STATUS_RUNNING = 20;
    const STATUS_ERROR = 30;
    const STATUS_PENDING_IMPORT = 35;
    const STATUS_COMPLETE = 40;

    /**
     * @var array
     */
    private static $_statuses = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_RUNNING => 'Running',
        self::STATUS_PENDING_IMPORT => 'Pending Import',
        self::STATUS_COMPLETE => 'Complete',
        self::STATUS_ERROR => 'Error',
    ];

    /**
     * @var UploadedFile|null
     */
    public $file;

    /**
     * @var array column mapping
     */
    public $columnMap = [];

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(self::$_statuses, $this->status_id);
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return self::$_statuses;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['import_model'], 'required'],
            [['status_id', 'abort', 'progress', 'error_count', 'total_rows', 'processed_rows'], 'integer'],
            [['started_at', 'created_at'], 'safe'],
            [['filename', 'data'], 'string'],

            [['import_model'], 'string', 'max' => 255],
            [['status_id'], 'default', 'value' => self::STATUS_PENDING],
            [
                ['file'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'csv',
                'checkExtensionByMimeType' => false,
                'on' => self::SCENARIO_INSERT
            ],
            [['file'], 'safe', 'on' => self::SCENARIO_INSERT],
            [['data'], 'validateCSV', 'on' => self::SCENARIO_INSERT],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'status_id' => 'Status',
            'file' => 'Import File'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
                'updatedAtAttribute' => null,
            ]
        ];
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->total_rows > 0 ? round(((int)$this->processed_rows / (int)$this->total_rows) * 100, 2) : 0;
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (null !== ($this->file = UploadedFile::getInstance($this, 'file'))) {
            if ($this->file->error !== UPLOAD_ERR_OK) {
                $this->addError('file', 'Upload Failed');
                $this->file = null;
            } else {
                $this->filename = $this->file->baseName . '.' . $this->file->extension;
                $this->data = file_get_contents($this->file->tempName);
            }
        }

        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->total_rows = $this->getTotalRows();
        }

        return parent::beforeSave($insert);
    }

    public function beginProcess()
    {
        $command = sprintf('php %s importer/start %s > /dev/null &', Yii::getAlias('@app/yii'), $this->id);
        exec($command);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->beginProcess();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return BaseImportModel|ImportInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getModelInstance()
    {
        return Yii::createObject([
            'class' => $this->import_model
        ]);
    }

    public function getModelStatic()
    {
        $class = $this->import_model;
        return $class;
    }

    /**
     * @return Reader
     */
    public function validateCSV()
    {
        $csv = $this->createReader();
        $csv->setHeaderOffset(1);
        $headers = array_filter($csv->getHeader());
        $model = $this->getModelInstance();

        $this->columnMap = $model->attributes;

        if (method_exists($model, 'getColumnMap')) {
            $this->columnMap = $model->getColumnMap();
        }

        $requiredAttributes = [];
        $ignoredAttributes = ['id', 'import_id', 'import_status_id', 'import_error'];
        $oldScenario = $model->scenario;
        $model->setScenario(self::SCENARIO_IMPORT_VALIDATE);
        foreach($model->attributes as $name => $value) {
            if (!in_array($name, $ignoredAttributes) && $model->isAttributeRequired($name)) {
                $requiredAttributes[] = ArrayHelper::getValue($this->columnMap, $name, $name);
            }
        }
        $model->setScenario($oldScenario);

        $headerMapping = array_combine($headers, $headers);
        foreach($headerMapping as $headerAttr) {
            if (isset($this->columnMap[$headerAttr])) {
                $headerMapping[$headerAttr] = $this->columnMap[$headerAttr];
            }
        }
        $this->columnMap = $headerMapping;

        foreach($requiredAttributes as $attr) {
            if (!in_array($attr, $headerMapping)) {
                $this->addError('file', 'Required attribute '.$attr.' not found in file');
            }
        }

        return $csv;
    }

    /**
     * @return int
     */
    public function getTotalRows()
    {
        $csv = Reader::createFromString($this->data);
        $csv->setHeaderOffset(1);
        return count($csv);
    }

    /**
     * @param string $format
     * @return string
     */
    public function getTimeElapsed($format = '%H:%I:%S')
    {
        return (new \DateTime($this->started_at))->diff(new \DateTime())->format($format);
    }

    public function beforeProcess()
    {
        $this->status_id = Import::STATUS_RUNNING;
        $this->processed_rows = 0;
        $this->error_count = 0;
        $this->progress = 0;
        $this->abort = 0;
        $this->started_at = new \yii\db\Expression('CURRENT_TIMESTAMP');

        $class = $this->import_model;
        $class::deleteAll(['import_id' => $this->id]);

        $this->save(false);
        $this->refresh();
    }


    /**
     * @throws \Exception
     */
    public function process()
    {
        $this->beforeProcess();
        $this->processRows();

        // if abort flag was set, delete everything.
        if ($this->abort) {
            $this->delete();
            return;
        }

        if ($this->status_id == Import::STATUS_RUNNING) {
            $this->status_id = ($this->error_count) ? Import::STATUS_ERROR : Import::STATUS_PENDING_IMPORT;
            $this->update(false, ['status_id']);
        }
    }

    /**
     * @return Reader
     */
    private function createReader()
    {
        return Reader::createFromString($this->data);
    }

    public function processRows()
    {
        //$csv = $this->createReader();

        $csv = $this->validateCSV(); // revalidate CSV to generate mappings

        // Add filter to skip empty rows
        /*
        $csv->addFilter(function ($row) {
            return strlen(trim(implode('', $row))) > 0;
        });
        */

        $columnMap = $this->columnMap;
        $columns = array_keys($columnMap);

        gc_enable(); // make sure garbage collector is on
        $memUsage = memory_get_usage(true); // memUsage should remain relatively static

        foreach($csv as $index => $row) {
            $this->refresh();
            if ($this->abort) {
                return false;
            }

            try {
                $row = array_combine($columns, $row);
                if (!$this->processRow($row, $columnMap, $index)) {
                    $this->error_count++;
                }
            } catch (\yii\base\Exception $e) {
                Yii::error($e->getMessage());
                $this->status_id = Import::STATUS_ERROR;
                continue;
            }
            /**
             * I've seen memory usage spike horrendously, manually fire the garbage collector
             * if we're 20mb over the starting point
             */
            if (($memUsage + 20000000) < memory_get_usage(true)) {
                gc_collect_cycles(); // manually run the garbage collector,
                $memUsage = memory_get_usage(true); // reset to the starting memory point
            }

            $this->processed_rows++;
            $this->progress = $this->getPercent();
            $this->update(false, ['processed_rows', 'progress', 'status_id', 'error_count']);

        }
    }

    /**
     * @param $row
     * @param $columnMap
     * @return bool
     * @throws \yii\base\Exception
     */
    public function processRow($row, $columnMap, $index)
    {
        $model = $this->getModelInstance();
        $model->import_id = $this->id;
        $model->import_status_id = BaseImportModel::STATUS_SUCCESS;

        $model->setScenario(static::SCENARIO_IMPORT_VALIDATE);

        $model->trigger(BaseImportModel::EVENT_BEFORE_PROCESS_ROW, new ImportEvent([
            'import' => $this,
            'row' => $row,
            'rowIndex' => $index,
        ]));

        echo '.';
        $unmapped = [];
        foreach ($row as $colName => $value) {
            $attr = $columnMap[$colName];
            if ($model->hasAttribute($attr)) {
                $model->$attr = trim($value);
            } elseif ($value) {
                $unmapped[$attr ? $attr : $colName] = $value;
            }
        }

        $model->trigger(BaseImportModel::EVENT_AFTER_PROCESS_ROW, new ImportEvent([
            'import' => $this,
            'row' => $row,
            'rowIndex' => $index,
            'unmapped' => $unmapped
        ]));

        if (!$model->validate()) {
            $model->import_error = $model->generateErrorSummary();
            $model->import_status_id = BaseImportModel::STATUS_ERROR;
        }

        $model->setScenario(ActiveRecord::SCENARIO_DEFAULT);
        if (!$model->save()) {
            throw new \yii\base\Exception('Unable to save record ' . json_encode($model->errors));
        }
        $import_status_id = $model->import_status_id;
        unset($model);
        unset($unmapped);


        return $import_status_id != BaseImportModel::STATUS_ERROR;
    }

    /**
     * @param $import_id
     * @param bool $commit
     * @return array
     * @throws \yii\db\Exception
     */
    public function importRecords($commit = true)
    {
        $results = [];
        $success = true;
        $tx = Yii::$app->db->beginTransaction();
        $model = $this->getModelInstance();

        try {
            foreach($model->getImportQueries($this->id) as $importQuery) {
                $importQuery->execute(Yii::$app->db);
                $results[$importQuery->name] = $importQuery;
            }
        } catch (\yii\base\Exception $e) {
            $results['error'] = $e->getMessage();
            $success = false;
        }

        $success && $commit ? $tx->commit() : $tx->rollBack();
        return $results;
    }

}
