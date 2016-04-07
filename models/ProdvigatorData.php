<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prodvigator_data".
 *
 * @property string $id
 * @property string $domain
 * @property integer $keywords
 * @property integer $traff
 * @property integer $new_keywords
 * @property integer $out_keywords
 * @property integer $rised_keywords
 * @property integer $down_keywords
 * @property double $visible
 * @property double $cost_min
 * @property double $cost_max
 * @property integer $ad_keywords
 * @property integer $ads
 * @property string $date
 * @property integer $modified_at
 */
class ProdvigatorData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prodvigator_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'domain', 'keywords', 'traff', 'new_keywords', 'out_keywords', 'rised_keywords', 'down_keywords', 'visible', 'cost_min', 'cost_max', 'ad_keywords', 'ads', 'date'], 'required'],
            [['keywords', 'traff', 'new_keywords', 'out_keywords', 'rised_keywords', 'down_keywords', 'ad_keywords', 'ads', 'modified_at'], 'integer'],
            [['visible', 'cost_min', 'cost_max'], 'number'],
            [['id', 'domain', 'date'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'keywords' => 'Keywords',
            'traff' => 'Traff',
            'new_keywords' => 'New Keywords',
            'out_keywords' => 'Out Keywords',
            'rised_keywords' => 'Rised Keywords',
            'down_keywords' => 'Down Keywords',
            'visible' => 'Visible',
            'cost_min' => 'Cost Min',
            'cost_max' => 'Cost Max',
            'ad_keywords' => 'Ad Keywords',
            'ads' => 'Ads',
            'date' => 'Date',
        ];
    }
}
