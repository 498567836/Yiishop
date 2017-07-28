<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $username;
    public $repassword;
    public $oldpassword;
    public $roles;
    //const SCENARIO_LOGIN = 'login';
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_EDITSELF = 'editself';
    public static function getRoles(){
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(),'name','name');
    }
    public $getstatus=[1=>'正常',0=>'禁用'];
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','email','status'], 'required','message'=>'{attribute}必填'],
            [['oldpassword'], 'required','on' =>[self::SCENARIO_EDITSELF],'message'=>'{attribute}必填'],
            [['password'], 'required','on' =>[self::SCENARIO_ADD,self::SCENARIO_EDITSELF] ,'message'=>'{attribute}必填'],
            [['repassword'], 'required','on' =>[self::SCENARIO_ADD,self::SCENARIO_EDITSELF] ,'message'=>'{attribute}必填'],
            [['name'], 'string', 'max' => 20, 'min' => 5],
            [['password','repassword'], 'string', 'max' => 100, 'min' => 5],
            [['name'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['roles'], 'safe'],
            [['repassword'],'compare', 'compareAttribute'=>'password','on' =>[self::SCENARIO_ADD,self::SCENARIO_EDIT,self::SCENARIO_EDITSELF],'message'=>'两次密码必须一致'],
            //使用自定义函数过滤
            ['repassword', 'filter', 'filter' => function() { // 在此处标准化输入的email
                if($this->password){
                    return 'required';
                }
                }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '用户名',
            'password' => '密码',
            'repassword' => '确认密码',
            'email' => '邮箱',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'oldpassword' => '旧密码',
            'roles' => '角色',
            'status' => '状态',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->auth_key==$authKey;
    }
}
