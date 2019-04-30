<?php

namespace backend\models;

use app\models\DialResult;
use app\models\DispIdent;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dial_people".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $mo
 * @property integer $user_o
 * @property string $description
 * @property integer $result
 * @property string $dt
 * @property string $range
 */
class DialPeople extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_people';
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'dt',
                ],
                'value' => function ($event) {
                    return date('Y-m-d');
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
            [['pid'], 'required'],
            [['description', 'range'], 'string'],
            [['pid', 'mo', 'user_o', 'result'], 'integer'],
            [['dt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'mo' => 'Mo',
            'user_o' => 'User O',
            'description' => 'Description',
            'result' => 'Result',
            'dt' => 'Dt',
            'range' => 'Range',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(Login::className(), ['id' => 'user_o']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(DialAnswers::className(), ['people_id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmo()
    {
        return $this->hasOne(DispIdent::className(), ['pid' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(DialResult::className(), ['id' => 'result']);
    }

    /**
     * @param $id
     */
    public static function setStatus($id, $mo)
    {
        $model = self::findOne(['pid' => $id]);

        if(!$model)
        {
            DialPeople::createPeople($id, $mo);
        }
    }

    /**
     * новая запись в таблице опроса диспансеризации
     * @param $id
     */
    public static function createPeople($id, $mo)
    {
        $model = new self();
        $model->pid = $id;
        $model->mo = $mo;
        $model->user_o = Yii::$app->user->id;
        $model->result = 12;
        $model->save();
    }
}