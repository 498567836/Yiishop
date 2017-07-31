<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $province
 * @property string $city
 * @property string $zone
 * @property string $address
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address','tel','username','province', 'city', 'zone'], 'required','message'=>'{attribute}必填'],
            [['user_id'], 'integer'],
            [[ 'status'], 'safe'],
//            [[ 'province', 'city', 'zone'], 'safe'],
            [['province', 'city', 'zone'], 'string', 'max' => 10],
            [['address','username','tel'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人',
            'tel' => '电话号码',
            'user_id' => '用户ID',
            'province' => '省份',
            'city' => '城市',
            'zone' => '区县',
            'address' => '详细地址',
            'status' => '设为默认地址',
        ];
    }
}
