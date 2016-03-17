<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class User extends ActiveRecord implements IdentityInterface
{
    public $isActive;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    const STATUS_ACTIVE = 10;
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
            [['title', 'password', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
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

    public static function findByUsername($username)
    {
        return self::findOne(['title' => $username]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds half_user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds half_user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByID($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds half_user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Returns calculated age based on the timestamp
     * @return bool|string
     */
    public function getCalculatedAge()
    {
        $age = date('Y') - date('Y', $this->birthday);
        if (date('md', $this->birthday) > date('md')) {
            $age--;
        }
        return $age;
    }

    /**
     * Returns user's model primary key - ID
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns users's auth key
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * Validates user's auth key
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Set rbac role for current User as "author"
     * @inheritdoc
     */
    public function setRole(){
        $auth = Yii::$app->authManager;
        $userRole = $auth->getRole('coworker');
        $auth->assign($userRole, $this->getId());
        return true;
    }

    /**
     * Set rbac role for current User as "author"
     * @inheritdoc
     */
    public function setClientRole(){
        $auth = Yii::$app->authManager;
        $userRole = $auth->getRole('client');
        $auth->assign($userRole, $this->getId());
        return true;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Saves into database's table `address` all adresses which came up from POST array
     * @param $post
     */
    public function setAddress($post){
        Address::deleteAll(['user_id' => $this->id]);
        foreach($post as $keyPost => $valPost){
            if(preg_match('/^desc_/', $keyPost)){
                $addr = new Address();
                $addr->user_id = $this->id;
                $addr->address = $valPost;
                $addr->save();
            }
        }
    }

    /**
     * Saves into database's table `telephone` all adresses which came up from POST array
     * @param $post
     */
    public function setTelephone($post){
        Telephone::deleteAll(['user_id' => $this->id]);
        foreach($post as $keyTel => $valTel){

            if(preg_match('/^tels_/', $keyTel)){
                $tel = new Telephone();
                $tel->user_id = $this->id;
                $tel->telephone = $valTel;
                $tel->description = Yii::$app->getRequest()->post('tdesc_' . substr($keyTel, 5));
                $tel->save();
            }
        }
    }

    /**
     * Saves into database's table `company_details` all details fields which came up from POST array
     * @param $post - POSt
     */
    public function setCompanyDetails($post){
        $details = CompanyDetails::find()->where(['user_id' => $this->id])->one();
        if(!$details){
            $details = new CompanyDetails();
        }
        $details->user_id = $this->id;
        $details->okpo = $post['okpou'];
        $details->requisite_details = $post['company-details'];
        $details->save();
    }

    /**
     * Return associated Address models
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }

    /**
     * Returns associated Telephone models.
     * @return \yii\db\ActiveQuery
     */
    public function getTelephones()
    {
        return $this->hasMany(Telephone::className(), ['user_id' => 'id']);
    }

    /**
     * Returns associated CompanyDetails model.
     * @return \yii\db\ActiveQuery
     */
    public function getDetails(){
        return $this->hasOne(CompanyDetails::className(), ['user_id' => 'id']);
    }

    /**
     * Returns associated array of Telephone models.
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSipphones(){
        return Telephone::find()->where(['user_id' => $this->id])->all();
    }

}
