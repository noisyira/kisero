<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stickMO".
 *
 * @property integer $id
 * @property integer $peopleid
 * @property string $PodMOCode
 * @property string $MOCode
 * @property string $DateBegin
 * @property string $DateEnd
 * @property integer $Reason
 * @property string $UchCode
 * @property string $DocCode
 * @property string $StickDate
 * @property string $file_name
 * @property string $send_date
 * @property integer $snils_correct
 * @property integer $doc_correction
 */
class StickMO extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stickMO';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('erz');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['peopleid'], 'required'],
            [['peopleid', 'Reason', 'snils_correct', 'doc_correction'], 'integer'],
            [['PodMOCode', 'MOCode', 'UchCode', 'DocCode', 'file_name'], 'string'],
            [['DateBegin', 'DateEnd', 'StickDate', 'send_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'peopleid' => 'Peopleid',
            'PodMOCode' => 'Pod Mocode',
            'MOCode' => 'Mocode',
            'DateBegin' => 'Date Begin',
            'DateEnd' => 'Date End',
            'Reason' => 'Reason',
            'UchCode' => 'Uch Code',
            'DocCode' => 'Doc Code',
            'StickDate' => 'Stick Date',
            'file_name' => 'File Name',
            'send_date' => 'Send Date',
            'snils_correct' => 'Snils Correct',
            'doc_correction' => 'Doc Correction',
        ];
    }

    /**
     * Реестр
     * @return \yii\db\ActiveQuery
     */
    public function getMo()
    {
        return $this->hasOne(MO::className(), ['KODMO' => 'MOCode']);
    }
}
