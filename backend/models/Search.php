<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 */
class Search extends ActiveRecord
{
    public $search;
    private $table;
    function __constrct($tablename){
        $this->table=$tablename;
    }
    public static function tableName()
    {
        return self::$table;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }
}
