<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "monitoring".
 *
 * @property integer $id
 * @property string $dt
 * @property string $range
 * @property string $text
 */
class Monitoring extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt']
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt'], 'safe'],
            [['range', 'text'], 'required'],
            [['range', 'text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt' => 'Дата внесения изменений',
            'range' => 'Выберите период',
            'text' => 'Текст сообщения',
        ];
    }
}
