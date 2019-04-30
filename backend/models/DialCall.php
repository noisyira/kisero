<?php

namespace app\models;

use backend\models\Cdr;
use Yii;

/**
 * This is the model class for table "dial_call".
 *
 * @property integer $id
 * @property integer $dial_people_id
 * @property string $phone
 * @property string $datetime
 * @property string $user_src
 * @property string $channel_id
 */
class DialCall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_call';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dial_people_id'], 'integer'],
            [['phone', 'user_src', 'channel_id'], 'string'],
            [['datetime'], 'required'],
            [['datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dial_people_id' => 'Dial People ID',
            'phone' => 'Phone',
            'datetime' => 'Datetime',
            'user_src' => 'User_src',
            'channel_id' => 'Channel ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(Cdr::className(), ['uniqueid' => 'channel_id'])
            ->orderBy([
                'duration' => SORT_DESC,
            ]);
    }
}
