<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dial_answers".
 *
 * @property integer $id
 * @property integer $people_id
 * @property string $question_key
 * @property integer $answer_key
 * @property integer $result
 */
class DialAnswers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_answers';
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($this->question_key == '2.1')
            {
                $model = DialPeople::findOne(['pid' => $this->people_id]);
                $model->range = $this->answer_key;
                $model->update();
            }
            return true;
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['people_id'], 'required'],
            [['people_id', 'answer_key', 'result'], 'integer'],
            [['question_key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'people_id' => 'People ID',
            'question_key' => 'Question Key',
            'answer_key' => 'Answer Key',
            'result' => 'Result',
        ];
    }

    public static function reset($id)
    {
        $model = self::find()->where(['people_id' => $id])->all();

        $model->answer_key = null;
        $model->updateAll();
    }
}
