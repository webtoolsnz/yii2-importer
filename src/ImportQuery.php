<?php

namespace webtoolsnz\importer;

/**
 * Class ImportQuery
 * @package webtoolsnz\importer
 */
class ImportQuery extends \yii\base\Component
{
    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $description;

    /**
     * @var
     */
    public $query;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @var integer
     */
    public $results = 0;

    /**
     * @param \yii\db\Connection $db
     * @throws \yii\db\Exception
     */
    public function execute(\yii\db\Connection $db)
    {
        $this->results = $db->createCommand($this->query, $this->params)->execute();
    }
}