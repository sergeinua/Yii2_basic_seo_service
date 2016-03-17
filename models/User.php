<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 * @property string $email
 * @property integer $created_at
 * @property integer $role
 * @property string $authKey
 */

/**
 * Class User
 * roles:
 * 1 - admin
 * 2 - seo
 * 3 - user
 * @package app\models
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'created_at', 'role'], 'required'],
            [['created_at'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['firstName', 'lastName', 'authKey'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 200],
            [['email'], 'email'],
            [['role'], 'string'] // TODO : check migration for this
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'password' => 'Password',
            'created_at' => 'Created At',
            'role' => 'Role',
            'authKey' => 'Auth Key',
            'email' => 'email',
        ];
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        throw new NotSupportedException();//I don't implement this method because I don't have any access token column in my database
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return $this->authKey;//Here I return a value of my authKey column
    }

    public function validateAuthKey($authKey){
        return $this->authKey === $authKey;
    }
    public static function findByUsername($username){
        return self::findOne(['username'=>$username]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function getRolename()
    {
        switch($this->role){
            case 1:
                $role = 'Admin';
                break;
            case 2:
                $role = 'SEO';
                break;
            case 3:
                $role = 'User';
                break;
        }
        return $role;
    }
}
