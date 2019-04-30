<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "statement_action".
 *
 * @property integer $id
 * @property string $channel_id
 * @property integer $user_id
 * @property string $dt
 * @property string $status
 * @property string $accept
 * @property string $msg
 */
class StatementAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statement_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_id', 'user_id', 'dt'], 'required'],
            [['channel_id', 'status', 'accept', 'msg'], 'string'],
            [['user_id'], 'integer'],
            [['dt'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'user_id' => 'User ID',
            'dt' => 'Dt',
            'status' => 'Status',
            'accept' => 'Accept',
            'msg' => 'Msg',
        ];
    }
}
