<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stmt_call".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property string $channel_id
 * @property integer $in_user
 * @property integer $send_user
 */
class StmtCall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_call';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id', 'channel_id', 'in_user'], 'required'],
            [['stmt_id', 'in_user', 'send_user'], 'integer'],
            [['channel_id'], 'string'],
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
            'channel_id' => 'Channel ID',
            'in_user' => 'In User',
            'send_user' => 'Send User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallUID()
    {
        return $this->hasOne(Cdr::className(),  ['uniqueid' => 'channel_id'])
            ->where(['disposition' => 'ANSWERED']);
    }

    public static function newCallStmt($stmt_id, $request)
    {
        $call_stmt = new StmtCall();
        
        $call_stmt->stmt_id = $stmt_id;
        $call_stmt->channel_id = $request->channel_id;
        $call_stmt->in_user = Yii::$app->user->id;
        $call_stmt->send_user = isset($request->transfer)?SipAccount::getUserID($request->send_user):null;

        $call_stmt->save();
    }

    public static function transferCallStmt($request, $channel)
    {
        $call_stmt = new StmtCall();

        $call_stmt->stmt_id = $request->id;
        $call_stmt->channel_id = $channel;
        $call_stmt->in_user = Yii::$app->user->id;
        $call_stmt->send_user = isset($request->transfer)?SipAccount::getUserID($request->transfer->sip_private_identity):null;

        $call_stmt->save();
    }
}
