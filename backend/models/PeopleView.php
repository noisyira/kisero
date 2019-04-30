<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peopleView".
 *
 * @property integer $id
 * @property string $filename
 * @property string $mo
 * @property integer $y
 * @property integer $n
 * @property string $result
 * @property string $insdate
 * @property integer $PeopleID
 * @property integer $fid
 * @property string $id_pac
 * @property string $disp
 * @property integer $pd
 * @property string $fam
 * @property string $im
 * @property string $ot
 * @property integer $w
 * @property string $dr
 * @property integer $doctype
 * @property string $docser
 * @property string $docnum
 * @property integer $vpolis
 * @property string $spolis
 * @property string $npolis
 * @property string $code_mo
 * @property integer $lpu_pod
 * @property string $coment
 * @property integer $cid
 * @property integer $pid
 * @property integer $smo
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
 * @property string $indt
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
 * @property integer $user_o
 * @property string $description
 * @property integer $resultDial
 * @property string $dt
 * @property string $range
 */
class PeopleView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peopleView';
    }


    public static function primaryKey()
    {
        return array('peopleID');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'filename'], 'required'],
            [['id', 'y', 'n', 'PeopleID', 'fid', 'pd', 'w', 'doctype', 'vpolis', 'lpu_pod', 'cid', 'pid', 'smo', 'sexMan', 'typeDoc', 'statusMan', 'regKladrId', 'activPolesId', 'liveKladrId', 'cr', 'lostman', 'livetemp', 'refugee', 'FFOMS', 'wid', 'pr_rab', 'user_o', 'resultDial'], 'integer'],
            [['filename', 'mo', 'result', 'id_pac', 'disp', 'fam', 'im', 'ot', 'docser', 'docnum', 'spolis', 'npolis', 'code_mo', 'coment', 'ENP', 'Name', 'sName', 'pName', 'pbMan', 'nationMan', 'serDoc', 'numDoc', 'addressReg', 'addressLive', 'snils', 'okato_mj', 'proxy_man', 'contact', 'orgdoc', 'okato_reg', 'kladr_reg', 'kladr_live', 'description', 'range'], 'string'],
            [['insdate', 'dr', 'dateMan', 'dateDoc', 'dateReg', 'dateDeath', 'indt', 'crdate', 'doc_date_end', 'dt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'mo' => 'Mo',
            'y' => 'Y',
            'n' => 'N',
            'result' => 'Result',
            'insdate' => 'Insdate',
            'PeopleID' => 'People ID',
            'fid' => 'Fid',
            'id_pac' => 'Id Pac',
            'disp' => 'Disp',
            'pd' => 'Pd',
            'fam' => 'Fam',
            'im' => 'Im',
            'ot' => 'Ot',
            'w' => 'W',
            'dr' => 'Dr',
            'doctype' => 'Doctype',
            'docser' => 'Docser',
            'docnum' => 'Docnum',
            'vpolis' => 'Vpolis',
            'spolis' => 'Spolis',
            'npolis' => 'Npolis',
            'code_mo' => 'Code Mo',
            'lpu_pod' => 'Lpu Pod',
            'coment' => 'Coment',
            'cid' => 'Cid',
            'pid' => 'Pid',
            'smo' => 'Smo',
            'ENP' => 'Enp',
            'Name' => 'Name',
            'sName' => 'S Name',
            'pName' => 'P Name',
            'dateMan' => 'Date Man',
            'sexMan' => 'Sex Man',
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
            'indt' => 'Indt',
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
            'user_o' => 'User O',
            'description' => 'Description',
            'resultDial' => 'Result Dial',
            'dt' => 'Dt',
            'range' => 'Range',
        ];
    }

    /**
     * disp_file_action
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasMany(DispFileAction::className(), ['id_pac' => 'id_pac']);
    }

    /**
     * disp_file_action
     * @return \yii\db\ActiveQuery
     */
    public function getResultDial()
    {
        return $this->hasOne(DialResult::className(), ['id' => 'resultDial']);
    }
}