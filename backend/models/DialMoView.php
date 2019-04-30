<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dialMoView".
 *
 * @property string $KODMO
 * @property string $KODOKATO
 * @property string $NAMMO
 * @property string $ADRESMO
 * @property integer $total
 */
class DialMoView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dialMoView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KODMO'], 'required'],
            [['KODMO', 'KODOKATO', 'NAMMO', 'ADRESMO'], 'string'],
            [['total'], 'integer'],
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
            'total' => 'Total',
        ];
    }
}
