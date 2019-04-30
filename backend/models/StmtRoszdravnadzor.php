<?php

namespace backend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stmt_roszdravnadzor".
 *
 * @property integer $id
 * @property integer $user_o
 * @property integer $theme
 * @property string $dt
 * @property string $record
 *
 * @property MnThemeRoszdravnadzor $theme0
 */
class StmtRoszdravnadzor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_roszdravnadzor';
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
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_o'],
                ],
                'value' => function ($event) {
                    return Yii::$app->getUser()->id;
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
            [['theme'], 'required'],
            [['user_o', 'theme'], 'integer'],
            [['dt'], 'safe'],
            [['record'], 'string'],
            [['theme'], 'exist', 'skipOnError' => true, 'targetClass' => MnThemeRoszdravnadzor::className(), 'targetAttribute' => ['theme' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_o' => 'Пользователь',
            'theme' => 'Тема вопроса',
            'dt' => 'Дата/время',
            'record' => 'Запись',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme0()
    {
        return $this->hasOne(MnThemeRoszdravnadzor::className(), ['id' => 'theme']);
    }
}
