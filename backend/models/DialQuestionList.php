<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dial_question_list".
 *
 * @property integer $id
 * @property string $text
 * @property integer $form
 */
class DialQuestionList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_question_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'form'], 'required'],
            [['text'], 'string'],
            [['form'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'form' => 'Form',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(DialAnswerList::className(), ['q_id' => 'id']);
    }
}
