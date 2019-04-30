<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dial_params".
 *
 * @property integer $id
 * @property integer $user
 * @property string $params_mo
 * @property string $params_people
 * @property string $dt
 */
class DialParams extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_params';
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt'],
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
            [['user'], 'required'],
            [['user'], 'integer'],
            [['params_mo', 'params_people'], 'string'],
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
            'user' => 'User',
            'params_mo' => 'Params Mo',
            'params_people' => 'Params People',
            'dt' => 'Dt',
        ];
    }

    /**
     * @param $params
     */
    public static function saveParamsMO($params)
    {
        $model = self::findOne(['user' => Yii::$app->user->id]);
        if(!$model)
        {
            $model = new self();
        }

        $model->user = Yii::$app->user->id;
        $model->params_mo = serialize($params);

        $model->save();
    }

    public static function setParamsPeople($params)
    {
        $model = self::findOne(['user' => Yii::$app->user->id]);
        if(!$model)
        {
            $model = new self();
        }

        $model->user = Yii::$app->user->id;
        $model->params_people = serialize($params);

        $model->save();
    }
}
