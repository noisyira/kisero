<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dial_answer_list".
 *
 * @property integer $q_id
 * @property string $text
 * @property string $value
 */
class DialAnswerList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_answer_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q_id', 'text', 'value'], 'required'],
            [['q_id'], 'integer'],
            [['text', 'value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'q_id' => 'Q ID',
            'text' => 'Text',
            'value' => 'Value',
        ];
    }
}
