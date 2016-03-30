<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_city".
 *
 * @property integer $city_id
 * @property string $country_iso
 * @property integer $visits
 * @property integer $created_at
 */
class ApiCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'visits', 'created_at', 'country_iso'], 'required'],
            [['city_id', 'visits', 'created_at'], 'integer'],
            [['country_iso'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'visits' => 'Visits',
            'created_at' => 'Created At',
        ];
    }
}
