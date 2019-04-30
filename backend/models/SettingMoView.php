<?php

namespace app\models;

use backend\models\MO;
use Yii;

/**
 * This is the model class for table "SettingMoView".
 *
 * @property integer $user
 * @property string $KODMO
 * @property string $KODOKATO
 * @property string $NAMMO
 * @property string $ADRESMO
 * @property integer $smo
 * @property integer $y
 * @property integer $total
 */
class SettingMoView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SettingMoView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'KODMO'], 'required'],
            [['user', 'smo', 'y', 'total'], 'integer'],
            [['KODMO', 'KODOKATO', 'NAMMO', 'ADRESMO'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user' => 'User',
            'KODMO' => 'Kodmo',
            'KODOKATO' => 'Kodokato',
            'NAMMO' => 'Nammo',
            'ADRESMO' => 'Adresmo',
            'smo' => 'Smo',
            'y' => 'Y',
            'total' => 'Total',
        ];
    }

    /**
     * МО
     * @return \yii\db\ActiveQuery
     */
    public function getMo()
    {
        return $this->hasOne(MO::className(), ['KODMO' => 'KODMO']);
    }

    /**
     * peopleView
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasOne(PeopleView::className(), ['code_mo' => 'KODMO']);
    }
}
