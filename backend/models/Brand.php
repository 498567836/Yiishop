<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    public $logoFile;
    public static function status_options($option=false){
        $status=[
            //状态(-1删除 0隐藏 1正常)
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if ($option==false){
            unset($status[-1]);
        }
        return $status;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort','name','intro','status'], 'required','message'=>'{attribute}必填'],
            //[['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['status'], 'string' ],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
