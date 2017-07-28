<?php
namespace  backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    const SCENARIO_ADD = 'add';
    public function rules(){
        return[
            [['description'],'required'],
            [['name'],'required' ,'on'=>self::SCENARIO_ADD],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            //[['name'],'match','pattern'=>'^\w*\\\w*$','message'=>'路由格式不正确'],
        ];
    }
public function attributeLabels()
{
    return [
        'name'=>'名称/路由',
        'description'=>'描述'
    ];
}
public function validateName(){
        //var_dump(\Yii::$app->authManager->getPermission($this->name));exit;
    if(\Yii::$app->authManager->getPermission($this->name)!=null)
    $this->addError('name','该路由已存在');
}


}