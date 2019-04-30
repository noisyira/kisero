<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "people".
 *
 * @property integer $id
 * @property string $ENP
 * @property string $Name
 * @property string $sName
 * @property string $pName
 * @property string $dateMan
 * @property integer $sexMan
 * @property string $pbMan
 * @property string $nationMan
 * @property integer $typeDoc
 * @property string $serDoc
 * @property string $numDoc
 * @property string $dateDoc
 * @property integer $statusMan
 * @property integer $regKladrId
 * @property string $addressReg
 * @property string $dateReg
 * @property string $addressLive
 * @property integer $activPolesId
 * @property string $dateDeath
 * @property integer $liveKladrId
 * @property string $snils
 * @property string $okato_mj
 * @property string $InsDate
 * @property integer $cr
 * @property string $crdate
 * @property integer $lostman
 * @property string $proxy_man
 * @property string $contact
 * @property string $doc_date_end
 * @property integer $livetemp
 * @property integer $refugee
 * @property string $orgdoc
 * @property integer $FFOMS
 * @property integer $wid
 * @property string $okato_reg
 * @property string $kladr_reg
 * @property string $kladr_live
 * @property integer $pr_rab
 */
class People extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'people';
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
            [['ENP', 'Name', 'sName', 'pName', 'pbMan', 'nationMan', 'serDoc', 'numDoc', 'addressReg', 'addressLive', 'snils', 'okato_mj', 'proxy_man', 'contact', 'orgdoc', 'okato_reg', 'kladr_reg', 'kladr_live'], 'string'],
//            [['Name', 'sName', 'dateMan', 'sexMan'], 'required'],
            [['dateMan', 'dateDoc', 'dateReg', 'dateDeath', 'InsDate', 'crdate', 'doc_date_end'], 'safe'],
            [['sexMan', 'typeDoc', 'statusMan', 'regKladrId', 'activPolesId', 'liveKladrId', 'cr', 'lostman', 'livetemp', 'refugee', 'FFOMS', 'wid', 'pr_rab'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ENP' => 'Enp',
            'Name' => 'Имя',
            'sName' => 'Фамилия',
            'pName' => 'Отчество',
            'dateMan' => 'Дата рождения',
            'sexMan' => 'Пол',
            'pbMan' => 'Pb Man',
            'nationMan' => 'Nation Man',
            'typeDoc' => 'Type Doc',
            'serDoc' => 'Ser Doc',
            'numDoc' => 'Num Doc',
            'dateDoc' => 'Date Doc',
            'statusMan' => 'Status Man',
            'regKladrId' => 'Reg Kladr ID',
            'addressReg' => 'Address Reg',
            'dateReg' => 'Date Reg',
            'addressLive' => 'Address Live',
            'activPolesId' => 'Activ Poles ID',
            'dateDeath' => 'Date Death',
            'liveKladrId' => 'Live Kladr ID',
            'snils' => 'Snils',
            'okato_mj' => 'Okato Mj',
            'InsDate' => 'Ins Date',
            'cr' => 'Cr',
            'crdate' => 'Crdate',
            'lostman' => 'Lostman',
            'proxy_man' => 'Proxy Man',
            'contact' => 'Contact',
            'doc_date_end' => 'Doc Date End',
            'livetemp' => 'Livetemp',
            'refugee' => 'Refugee',
            'orgdoc' => 'Orgdoc',
            'FFOMS' => 'Ffoms',
            'wid' => 'Wid',
            'okato_reg' => 'Okato Reg',
            'kladr_reg' => 'Kladr Reg',
            'kladr_live' => 'Kladr Live',
            'pr_rab' => 'Pr Rab',
        ];
    }

    /**
     * Реестр
     * @return \yii\db\ActiveQuery
     */
    public function getReestr()
    {
        return $this->hasOne(Reestr::className(), ['ManId' => 'id'])
            ->where('statusPoles > 0');
    }

    /**
     * Реестр
     * @return \yii\db\ActiveQuery
     */
    public function getReestrs()
    {
        return $this->hasOne(Reestr::className(), ['ManId' => 'id'])
            ->where('statusPoles > 0')->andWhere(['orgId' => 2]) ;
    }

    /**
     * Прикрепление
     * @return \yii\db\ActiveQuery
     */
    public function getStik()
    {
        return $this->hasOne(StickMO::className(), ['peopleid' => 'id'])->with(['mo']);
    }

    public static function getSmoID($manID)
    {
        $smo = Reestr::find()->where(['ManId' => $manID])->andWhere(['>', 'statusPoles', 0])->one();

        switch ($smo->orgId) {
            case 1:
                return 3;
                break;
            case 2:
                return 2;
                break;
            default:
                return 1;
        }
    }
}
