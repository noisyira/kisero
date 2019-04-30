<?php

namespace backend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "eir_people".
 *
 * @property integer $id
 * @property string $params
 * @property string $nnapr
 * @property integer $result
 * @property string $panul
 * @property string $comment
 * @property integer $another_phone
 * @property integer $user_id
 * @property string $insdate
 */
class EirPeople extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eir_people';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['insdate'],
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
            [['params', 'nnapr', 'panul', 'comment'], 'string'],
            [['result', 'another_phone', 'user_id'], 'integer'],
            [['insdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'params' => 'Params',
            'nnapr' => 'Nnapr',
            'result' => 'Result',
            'panul' => 'Panul',
            'comment' => 'Comment',
            'another_phone' => 'Another Phone',
            'user_id' => 'User ID',
            'insdate' => 'Insdate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(EirPeopleCall::className(), ['pid' => 'id']);
    }
}

