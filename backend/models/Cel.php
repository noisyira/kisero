<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cel".
 *
 * @property integer $id
 * @property string $eventtype
 * @property string $eventtime
 * @property string $cid_name
 * @property string $cid_num
 * @property string $cid_ani
 * @property string $cid_rdnis
 * @property string $cid_dnid
 * @property string $exten
 * @property string $context
 * @property string $channame
 * @property string $appname
 * @property string $appdata
 * @property integer $amaflags
 * @property string $accountcode
 * @property string $uniqueid
 * @property string $linkedid
 * @property string $peer
 * @property string $userdeftype
 * @property string $extra
 */
class Cel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cel';
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
            [['eventtype', 'eventtime', 'cid_name', 'cid_num', 'cid_ani', 'cid_rdnis', 'cid_dnid', 'exten', 'context', 'channame', 'appname', 'appdata', 'amaflags', 'accountcode', 'uniqueid', 'linkedid', 'peer', 'userdeftype', 'extra'], 'required'],
            [['eventtime'], 'safe'],
            [['amaflags'], 'integer'],
            [['eventtype'], 'string', 'max' => 30],
            [['cid_name', 'cid_num', 'cid_ani', 'cid_rdnis', 'cid_dnid', 'exten', 'context', 'channame', 'appname', 'appdata', 'peer'], 'string', 'max' => 80],
            [['accountcode'], 'string', 'max' => 20],
            [['uniqueid', 'linkedid'], 'string', 'max' => 32],
            [['userdeftype'], 'string', 'max' => 255],
            [['extra'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eventtype' => 'Eventtype',
            'eventtime' => 'Eventtime',
            'cid_name' => 'Cid Name',
            'cid_num' => 'Cid Num',
            'cid_ani' => 'Cid Ani',
            'cid_rdnis' => 'Cid Rdnis',
            'cid_dnid' => 'Cid Dnid',
            'exten' => 'Exten',
            'context' => 'Context',
            'channame' => 'Channame',
            'appname' => 'Appname',
            'appdata' => 'Appdata',
            'amaflags' => 'Amaflags',
            'accountcode' => 'Accountcode',
            'uniqueid' => 'Uniqueid',
            'linkedid' => 'Linkedid',
            'peer' => 'Peer',
            'userdeftype' => 'Userdeftype',
            'extra' => 'Extra',
        ];
    }
}
