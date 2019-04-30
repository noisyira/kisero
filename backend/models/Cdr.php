<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cdr".
 *
 * @property string $calldate
 * @property string $clid
 * @property string $src
 * @property string $dst
 * @property string $dcontext
 * @property string $channel
 * @property string $dstchannel
 * @property string $lastapp
 * @property string $lastdata
 * @property integer $duration
 * @property integer $billsec
 * @property string $disposition
 * @property integer $amaflags
 * @property string $accountcode
 * @property string $uniqueid
 * @property string $userfield
 * @property string $did
 * @property string $recordingfile
 * @property string $cnum
 * @property string $cnam
 * @property string $outbound_cnum
 * @property string $outbound_cnam
 * @property string $dst_cnam
 */
class Cdr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdr';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('cdr');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['calldate'], 'safe'],
            [['duration', 'billsec', 'amaflags'], 'integer'],
            [['clid', 'src', 'dst', 'dcontext', 'channel', 'dstchannel', 'lastapp', 'lastdata'], 'string', 'max' => 80],
            [['disposition'], 'string', 'max' => 45],
            [['accountcode'], 'string', 'max' => 20],
            [['uniqueid'], 'string', 'max' => 32],
            [['userfield', 'recordingfile'], 'string', 'max' => 255],
            [['did'], 'string', 'max' => 50],
            [['cnum', 'cnam', 'outbound_cnum', 'outbound_cnam', 'dst_cnam'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'calldate' => 'Calldate',
            'clid' => 'Clid',
            'src' => 'Src',
            'dst' => 'Dst',
            'dcontext' => 'Dcontext',
            'channel' => 'Channel',
            'dstchannel' => 'Dstchannel',
            'lastapp' => 'Lastapp',
            'lastdata' => 'Lastdata',
            'duration' => 'Duration',
            'billsec' => 'Billsec',
            'disposition' => 'Disposition',
            'amaflags' => 'Amaflags',
            'accountcode' => 'Accountcode',
            'uniqueid' => 'Uniqueid',
            'userfield' => 'Userfield',
            'did' => 'Did',
            'recordingfile' => 'Recordingfile',
            'cnum' => 'Cnum',
            'cnam' => 'Cnam',
            'outbound_cnum' => 'Outbound Cnum',
            'outbound_cnam' => 'Outbound Cnam',
            'dst_cnam' => 'Dst Cnam',
        ];
    }
}
