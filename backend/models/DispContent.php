<?php

namespace backend\models;

use app\models\DialSetting;
use app\models\DispFileAction;
use app\models\DispFiles;
use app\models\DispIdent;
use Yii;

/**
 * This is the model class for table "disp_content".
 *
 * @property integer $id
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
 *
 * @property DispFiles $f
 * @property DispIdent[] $dispIdents
 */
class DispContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dsp_content';
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
            [['fid'], 'required'],
            [['fid', 'pd', 'w', 'doctype', 'vpolis', 'lpu_pod'], 'integer'],
            [['id_pac', 'disp', 'fam', 'im', 'ot', 'docser', 'docnum', 'snils', 'spolis', 'npolis', 'code_mo', 'coment'], 'string'],
            [['dr'], 'safe'],
            [['fid'], 'exist', 'skipOnError' => true, 'targetClass' => DispFiles::className(), 'targetAttribute' => ['fid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getF()
    {
        return $this->hasOne(DispFiles::className(), ['id' => 'fid'])->where(['result' => 5]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispIdent()
    {
        return $this->hasOne(DispIdent::className(), ['cid' => 'id'])->joinWith(['people', 'smo']);
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
    public function getDialPeople()
    {
        return $this->hasOne(DialPeople::className(), ['pid' => 'id'])->with(['status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasMany(DialAnswers::className(), ['people_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(DispFileAction::className(), ['id_pac' => 'id_pac']);
    }



}