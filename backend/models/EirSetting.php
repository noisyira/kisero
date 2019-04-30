<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "eir_setting".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $code_mo
 * @property string $params
 * @property string $range
 * @property string $from_date
 * @property string $to_date
 */
class EirSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eir_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['params', 'range', 'code_mo'], 'string'],
            [['from_date', 'to_date'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'code_mo' => 'Code Mo',
            'params' => 'Params',
            'range' => 'Range',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
        ];
    }
}