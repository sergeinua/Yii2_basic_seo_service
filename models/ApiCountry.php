<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_country".
 *
 * @property integer $id
 * @property integer $visits
 * @property string $country_iso
 * @property integer $project_id
 * @property integer $date
 */
class ApiCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visits', 'project_id', 'date'], 'integer'],
            [['project_id', 'date'], 'required'],
            [['country_iso'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visits' => 'Visits',
            'country_iso' => 'Country Iso',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
