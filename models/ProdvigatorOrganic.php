<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prodvigator_organic".
 *
 * @property integer $id
 * @property integer $region_queries_count
 * @property string $domain
 * @property string $keyword
 * @property string $url
 * @property string $right_spell
 * @property integer $dynamic
 * @property integer $found_results
 * @property integer $url_crc
 * @property double $cost
 * @property integer $concurrency
 * @property integer $position
 * @property integer $date
 * @property integer $keyword_id
 * @property string $subdomain
 * @property integer $region_queries_count_wide
 * @property string $types
 * @property integer $geo_names
 */
class ProdvigatorOrganic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prodvigator_organic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_queries_count', 'found_results', 'position', 'keyword_id'], 'integer'],
            [['cost'], 'number'],
            [['date', 'domain', 'region_queries_count_wide', 'geo_names'], 'string', 'max' => 500],
            [['keyword', 'url'], 'string', 'max' => 500],
            [['right_spell'], 'string', 'max' => 100],
            [['subdomain', 'types'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_queries_count' => 'Region Queries Count',
            'domain' => 'Domain',
            'keyword' => 'Keyword',
            'url' => 'Url',
            'right_spell' => 'Right Spell',
            'dynamic' => 'Dynamic',
            'found_results' => 'Found Results',
            'url_crc' => 'Url Crc',
            'cost' => 'Cost',
            'concurrency' => 'Concurrency',
            'position' => 'Position',
            'date' => 'Date',
            'keyword_id' => 'Keyword ID',
            'subdomain' => 'Subdomain',
            'region_queries_count_wide' => 'Region Queries Count Wide',
            'types' => 'Types',
            'geo_names' => 'Geo Names',
        ];
    }
}
