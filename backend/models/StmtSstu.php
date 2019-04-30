<?php

namespace backend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stmt_sstu".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property integer $user
 * @property string $dt
 */
class StmtSstu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_sstu';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dt',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'dt',
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
            [['stmt_id', 'user'], 'integer'],
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
            'stmt_id' => 'Stmt ID',
            'user' => 'User',
            'dt' => 'Dt',
        ];
    }

    public static function SaveSstu($stmt_id)
    {
        $data = StmtSstu::find()->where(['stmt_id' => $stmt_id])->one();
        $sstu = ($data)? $data : new StmtSstu();
        $sstu->stmt_id = $stmt_id;
        $sstu->user = Yii::$app->user->id;

        $sstu->save();
    }
}
