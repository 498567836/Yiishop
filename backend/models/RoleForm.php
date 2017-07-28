<?php
namespace  backend\models;
use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;
    const SCENARIO_ADD = 'add';
    public function rules(){
        return[
            [['description'],'required'],
            ['permissions','safe'],
            [['name'],'required' ,'on'=>self::SCENARIO_ADD],
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
public function attributeLabels()
{
    return [
        'name'=>'角色',
        'description'=>'描述',
        'permissions'=>'权限',
    ];
}
public function validateName(){
    if(\Yii::$app->authManager->getPermission($this->name))
    $this->addError('name','该角色已存在');
}


}