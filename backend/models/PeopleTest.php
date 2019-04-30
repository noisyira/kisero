<?php

namespace app\models;

use backend\models\Login;
use backend\models\MO;
use backend\models\People;
use backend\models\StickMO;
use Yii;
use yii\debug\models\search\Log;

/**
 * This is the model class for table "peopleTest".
 *
 * @property integer $peopleID
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
 * @property string $snils
 * @property integer $vpolis
 * @property string $spolis
 * @property string $npolis
 * @property string $code_mo
 * @property integer $lpu_pod
 * @property string $coment
 * @property integer $cid
 * @property integer $pid
 * @property integer $smo
 * @property string $contact
 * @property string $mo
 * @property integer $y
 * @property integer $n
 * @property string $result
 * @property string $insdate
 */
class PeopleTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peopleTest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['peopleID', 'fid', 'cid', 'pid', 'smo'], 'required'],
            [['peopleID', 'fid', 'pd', 'w', 'doctype', 'vpolis', 'lpu_pod', 'cid', 'pid', 'smo', 'y', 'n'], 'integer'],
            [['id_pac', 'disp', 'fam', 'im', 'ot', 'docser', 'docnum', 'snils', 'spolis', 'npolis', 'code_mo', 'coment', 'contact', 'mo', 'result'], 'string'],
            [['dr', 'insdate'], 'safe'],
        ];
    }

    public static function primaryKey()
    {
        return array('peopleID');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'peopleID' => 'peopleID',
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
            'snils' => 'Snils',
            'vpolis' => 'Vpolis',
            'spolis' => 'Spolis',
            'npolis' => 'Npolis',
            'code_mo' => 'Code Mo',
            'lpu_pod' => 'Lpu Pod',
            'coment' => 'Coment',
            'cid' => 'Cid',
            'pid' => 'Pid',
            'smo' => 'Smo',
            'contact' => 'Contact',
            'mo' => 'Mo',
            'y' => 'Y',
            'n' => 'N',
            'result' => 'Result',
            'insdate' => 'Insdate',
        ];
    }

    /**
     * МО
     * @return \yii\db\ActiveQuery
     */
    public function getMo()
    {
        return $this->hasOne(MO::className(), ['KODMO' => 'code_mo']);
    }

    /**
     * StickMO
     * @return \yii\db\ActiveQuery
     */
    public function getStick()
    {
        return $this->hasOne(StickMO::className(), ['MOCode' => 'code_mo', 'peopleid' => 'pid']);
    }

    /**
     * People
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasOne(People::className(), ['id' => 'pid']);
    }

    public function getIdent()
    {
        return $this->hasOne(DispIdent::className(), ['cid' => 'id'])
            ->select(['*', 'ROW_NUMBER() OVER( partition BY pid ORDER BY id DESC) rn'])
            ->with(['people'])
            ->where(['smo' => Login::getSmoId(Yii::$app->user->id)]);
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
