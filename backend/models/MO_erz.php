<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "MO".
 *
 * @property string $KODMO
 * @property string $KODOKATO
 * @property string $NAMMO
 * @property string $ADRESMO
 * @property integer $mo_type
 */
class MO_erz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MO';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('erz');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KODMO'], 'required'],
            [['KODMO', 'KODOKATO', 'NAMMO', 'ADRESMO'], 'string'],
            [['mo_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'KODMO' => 'Kodmo',
            'KODOKATO' => 'Kodokato',
            'NAMMO' => 'Nammo',
            'ADRESMO' => 'Adresmo',
            'mo_type' => 'Mo Type',
        ];
    }

    public static function getOptionsbyProvince($province_id) {

        $data = static::find()
            ->select(['KODMO as id','NAMMO as name'])
            ->where(['KODOKATO' => $province_id])
            ->orderBy(['KODMO'=>SORT_ASC])
            ->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
