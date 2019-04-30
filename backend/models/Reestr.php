<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "reestr".
 *
 * @property integer $id
 * @property string $ENumber
 * @property string $OldNumber
 * @property integer $ManId
 * @property integer $orgId
 * @property integer $typePoles
 * @property string $beginDate
 * @property string $endDate
 * @property string $stmDate
 * @property integer $stmType
 * @property integer $formPoles
 * @property integer $reasonStm
 * @property string $numBlank
 * @property string $numCard
 * @property integer $statusPoles
 * @property string $AnnulDate
 * @property string $InsDate
 * @property integer $cr
 * @property string $crdate
 * @property string $crcomment
 * @property integer $PVCode
 * @property integer $reasonG
 */
class Reestr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reestr';
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
            [['ENumber', 'OldNumber', 'numBlank', 'numCard', 'crcomment'], 'string'],
            [['ManId', 'orgId', 'typePoles', 'statusPoles'], 'required'],
            [['ManId', 'orgId', 'typePoles', 'stmType', 'formPoles', 'reasonStm', 'statusPoles', 'cr', 'PVCode', 'reasonG'], 'integer'],
            [['beginDate', 'endDate', 'stmDate', 'AnnulDate', 'InsDate', 'crdate'], 'safe'],
            [['ManId'], 'exist', 'skipOnError' => true, 'targetClass' => People::className(), 'targetAttribute' => ['ManId' => 'id']],
            [['orgId'], 'exist', 'skipOnError' => true, 'targetClass' => Smo::className(), 'targetAttribute' => ['orgId' => 'id']],
            [['typePoles'], 'exist', 'skipOnError' => true, 'targetClass' => Typepoles::className(), 'targetAttribute' => ['typePoles' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ENumber' => 'Enumber',
            'OldNumber' => 'Old Number',
            'ManId' => 'Man ID',
            'orgId' => 'Org ID',
            'typePoles' => 'Type Poles',
            'beginDate' => 'Begin Date',
            'endDate' => 'End Date',
            'stmDate' => 'Stm Date',
            'stmType' => 'Stm Type',
            'formPoles' => 'Form Poles',
            'reasonStm' => 'Reason Stm',
            'numBlank' => 'Num Blank',
            'numCard' => 'Num Card',
            'statusPoles' => 'Status Poles',
            'AnnulDate' => 'Annul Date',
            'InsDate' => 'Ins Date',
            'cr' => 'Cr',
            'crdate' => 'Crdate',
            'crcomment' => 'Crcomment',
            'PVCode' => 'Pvcode',
            'reasonG' => 'Reason G',
        ];
    }

    public static function getOrg($enp)
    {
        $data = self::find()->where(['ENumber' => $enp])->one();

        return $data->orgId;
    }
}
