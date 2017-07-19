<?php
namespace backend\models;
use  yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
class Articledetail extends ActiveRecord{
    public static function tableName()
    {
        return 'article_detail';
    }
    public function rules(){
        return [
            [['content'],'required','message'=>'{attribute}必填'],
        ];
    }
    public function attributeLabels(){
        return [
            'content'=>'文章内容',
        ];
    }


}