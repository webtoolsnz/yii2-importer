<?php

namespace webtoolsnz\importer\models\base;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the base-model class for table "import".
 *
 * @property integer $id
 * @property integer $status_id
 * @property string $import_model
 * @property integer $abort
 * @property integer $progress
 * @property string $started_at
 * @property string $created_at
 * @property integer $error_count
 * @property string $filename
 * @property string $data
 * @property integer $total_rows
 * @property integer $processed_rows
 *
 */
class Import extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import';
    }

    /**
     *
     */
    public static function label($n = 1)
    {
        return Yii::t('app', '{n, plural, =1{Import} other{Imports}}', ['n' => $n]);
    }

    /**
     *
     */
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'import_model', 'progress', 'created_at', 'error_count', 'filename', 'data', 'total_rows', 'processed_rows'], 'required'],
            [['status_id', 'abort', 'progress', 'error_count', 'total_rows', 'processed_rows'], 'integer'],
            [['started_at', 'created_at'], 'safe'],
            [['filename', 'data'], 'string'],
            [['import_model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_id' => 'Status',
            'user_id' => 'User',
            'import_model' => 'Import Model',
            'abort' => 'Abort',
            'progress' => 'Progress',
            'started_at' => 'Started At',
            'created_at' => 'Created At',
            'error_count' => 'Error Count',
            'filename' => 'Filename',
            'data' => 'Data',
            'total_rows' => 'Total Rows',
            'processed_rows' => 'Processed Rows',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = null)
    {
        $query = self::find();

        if ($params === null) {
            $params = array_filter(Yii::$app->request->get($this->formName(), array()));
        }

        $this->attributes = $params;

        $query->andFilterWhere([
            'import.id' => $this->id,
            'import.status_id' => $this->status_id,
            'import.abort' => $this->abort,
            'import.progress' => $this->progress,
            'import.error_count' => $this->error_count,
            'import.total_rows' => $this->total_rows,
            'import.processed_rows' => $this->processed_rows,
            'import.import_model' => $this->import_model,
        ]);

        $query->andFilterWhere(['like', 'import.started_at', $this->started_at])
            ->andFilterWhere(['like', 'import.created_at', $this->created_at])
            ->andFilterWhere(['like', 'import.filename', $this->filename])
            ->andFilterWhere(['like', 'import.data', $this->data]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);
    }
}

