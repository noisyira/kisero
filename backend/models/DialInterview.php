<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dial_interview".
 *
 * @property integer $id
 * @property integer $dial_people_id
 * @property integer $use_call
 * @property integer $type
 * @property integer $service
 * @property integer $info
 * @property integer $comity
 * @property integer $waiting
 * @property integer $get
 * @property string $description
 */
class DialInterview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_interview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dial_people_id', 'use_call', 'type', 'service', 'info', 'comity', 'waiting', 'get'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dial_people_id' => 'Dial People ID',
            'use_call' => 'Use Call',
            'type' => 'Type',
            'service' => 'Service',
            'info' => 'Info',
            'comity' => 'Comity',
            'waiting' => 'Waiting',
            'get' => 'Get',
            'description' => 'Description',
        ];
    }
}
