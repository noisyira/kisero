<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "answer_script".
 *
 * @property integer $id
 * @property string $name
 * @property string $group
 * @property string $key_statement
 * @property string $answer
 * @property string $recomend_users
 */
class AnswerScript extends \yii\db\ActiveRecord
{
    public $group;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer_script';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'key_statement', 'answer'], 'required'],
            [['name', 'key_statement', 'answer'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование сценария',
            'group' => 'Тип обращения',
            'key_statement' => 'Тема обращения',
            'answer' => 'Типовой сценарий ответа',
            'recomend_users' => 'Пользователи для ответа',
        ];
    }

    /**
     * Тема обращения
     * @return \yii\db\ActiveQuery
     */
    public function getStmt()
    {
        return $this->hasOne(MnStatement::className(), ['key_statement' => 'key_statement']);
    }
}
