<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $pid
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function getPermissions(){
        $array=\yii\helpers\ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','name');
        return array_merge([0=>'==选择权限=='],$array);
    }
    public static function getMenus(){
        $array=\yii\helpers\ArrayHelper::map(self::find()->select(['id','name'])->where('pid=0')->all(),'id','name');
        $array[0]='==顶级分类==';
        ksort($array);
        return $array;
    }
    public static function getParents(){
        return Menu::find()->where(['pid'=>0])->all();
    }
    public static function getChildren($id){
        return self::find()->where(['=','pid',$id])->all();
    }
//    public function getChildren(){
//        return $this->hasMany(self::className(),['pid','id']);
//    }
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sort'], 'integer'],
            [['name', 'url'], 'string', 'max' => 20],
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
            'url' => '权限/路由',
            'pid' => '上级菜单',
            'sort' => '排序',
        ];
    }
}
