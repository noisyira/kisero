<?php

namespace backend\models;

use app\models\Smo;
use Yii;

/**
 * This is the model class for table "disp_file_people".
 *
 * @property integer $id
 * @property string $id_pac_smo
 * @property string $id_pac
 * @property string $disp
 * @property integer $pd
 * @property string $fam
 * @property string $im
 * @property string $ot
 * @property string $dr
 * @property string $mobile
 * @property integer $vpolis
 * @property string $spolis
 * @property string $npolis
 * @property string $code_smo
 * @property string $code_mo
 * @property string $filename
 * @property string $realfilename
 * @property string $file_date
 * @property string $insdate
 */
class DispFilePeople extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'disp_file_people';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pac_smo', 'id_pac', 'disp', 'fam', 'im', 'ot', 'mobile', 'spolis', 'npolis', 'code_smo', 'code_mo', 'filename', 'realfilename'], 'string'],
            [['id_pac', 'insdate'], 'required'],
            [['pd', 'vpolis'], 'integer'],
            [['dr', 'file_date', 'insdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pac_smo' => 'Id Pac Smo',
            'id_pac' => 'Id Pac',
            'disp' => 'Disp',
            'pd' => 'Pd',
            'fam' => 'Fam',
            'im' => 'Im',
            'ot' => 'Ot',
            'dr' => 'Dr',
            'mobile' => 'Mobile',
            'vpolis' => 'Vpolis',
            'spolis' => 'Spolis',
            'npolis' => 'Npolis',
            'code_smo' => 'Code Smo',
            'code_mo' => 'Code Mo',
            'filename' => 'Filename',
            'realfilename' => 'Realfilename',
            'file_date' => 'File Date',
            'insdate' => 'Insdate',
        ];
    }

    /**
     * диспансеризация: действия по застрахованному
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(DispFileAction::className(), ['dfpi' => 'id'])->joinWith(['value']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmo()
    {
        return $this->hasOne(Smo::className(), ['Kod_smo' => 'code_smo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMo()
    {
        return $this->hasOne(MO::className(), ['KODMO' => 'code_mo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasOne(DialPeople::className(), ['pid' => 'id'])->with('status');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(DialAnswers::className(), ['people_id' => 'id']);
    }
}