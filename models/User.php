<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $is_juridical_person
 * @property integer $user_type
 * @property integer $status
 * @property integer $created_at
 */
class User extends \yii\db\ActiveRecord
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
            [['description'], 'string'],
            [['auth_key', 'password_hash', 'email', 'created_at'], 'required'],
            [['is_juridical_person', 'user_type', 'status', 'created_at'], 'integer'],
            [['title', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'is_juridical_person' => 'Is Juridical Person',
            'user_type' => 'User Type',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public static function findByUsername($username){
        return self::findOne(['title' => $username]);
    }

    public function validatePassword(){
        return true;
    }
}
