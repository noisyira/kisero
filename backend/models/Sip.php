<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sip".
 *
 * @property string $sip_private_identity
 * @property integer $id
 * @property string $name
 */
class Sip extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sip_private_identity', 'id', 'name'], 'required'],
            [['sip_private_identity', 'name'], 'string'],
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sip_private_identity' => 'Sip Private Identity',
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}
