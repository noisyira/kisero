<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pacient".
 *
 * @property integer $IDZAP
 * @property string $NNAPR
 * @property string $DNAPR
 * @property string $DPGOSP
 * @property string $DNGOSP
 * @property string $VNGOSP
 * @property string $DOGOSP
 * @property string $DANUL
 * @property integer $PANUL
 * @property integer $IANUL
 * @property integer $KANUL
 * @property integer $PMOANUL
 * @property string $FAM
 * @property string $IM
 * @property string $OT
 * @property integer $P
 * @property string $DR
 * @property string $TEL
 * @property integer $VPOLIS
 * @property string $SPOLIS
 * @property string $NPOLIS
 * @property string $TER
 * @property string $SMO
 * @property integer $FOMP
 * @property integer $TIPEXTR
 * @property integer $WRITEOUT
 * @property integer $MONAPR
 * @property integer $PMONAPR
 * @property integer $PROFONAPR
 * @property integer $PROFKNAPR
 * @property string $DS
 * @property string $MEDRAB
 * @property integer $MO
 * @property integer $PMO
 * @property integer $PROFO
 * @property integer $PROFK
 * @property string $NKART
 * @property string $DSPO
 * @property string $DPOGOSP
 * @property string $DSNAPR
 * @property string $RANUL
 */
class Pacient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pacient';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('gosp');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NNAPR', 'VNGOSP', 'FAM', 'IM', 'OT', 'TEL', 'SPOLIS', 'NPOLIS', 'TER', 'SMO', 'DS', 'NKART', 'DSPO', 'DSNAPR', 'RANUL'], 'string'],
            [['DNAPR', 'DPGOSP', 'DNGOSP', 'DOGOSP', 'DANUL', 'DR', 'DPOGOSP'], 'safe'],
            [['PANUL', 'IANUL', 'KANUL', 'PMOANUL', 'P', 'VPOLIS', 'FOMP', 'TIPEXTR', 'WRITEOUT', 'MONAPR', 'PMONAPR', 'PROFONAPR', 'PROFKNAPR', 'MO', 'PMO', 'PROFO', 'PROFK'], 'integer'],
            [['MEDRAB'], 'number'],
            [['NNAPR'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IDZAP' => 'Idzap',
            'NNAPR' => 'Nnapr',
            'DNAPR' => 'Dnapr',
            'DPGOSP' => 'Dpgosp',
            'DNGOSP' => 'Dngosp',
            'VNGOSP' => 'Vngosp',
            'DOGOSP' => 'Dogosp',
            'DANUL' => 'Danul',
            'PANUL' => 'Panul',
            'IANUL' => 'Ianul',
            'KANUL' => 'Kanul',
            'PMOANUL' => 'Pmoanul',
            'FAM' => 'Fam',
            'IM' => 'Im',
            'OT' => 'Ot',
            'P' => 'P',
            'DR' => 'Dr',
            'TEL' => 'Tel',
            'VPOLIS' => 'Vpolis',
            'SPOLIS' => 'Spolis',
            'NPOLIS' => 'Npolis',
            'TER' => 'Ter',
            'SMO' => 'Smo',
            'FOMP' => 'Fomp',
            'TIPEXTR' => 'Tipextr',
            'WRITEOUT' => 'Writeout',
            'MONAPR' => 'Monapr',
            'PMONAPR' => 'Pmonapr',
            'PROFONAPR' => 'Profonapr',
            'PROFKNAPR' => 'Profknapr',
            'DS' => 'Ds',
            'MEDRAB' => 'Medrab',
            'MO' => 'Mo',
            'PMO' => 'Pmo',
            'PROFO' => 'Profo',
            'PROFK' => 'Profk',
            'NKART' => 'Nkart',
            'DSPO' => 'Dspo',
            'DPOGOSP' => 'Dpogosp',
            'DSNAPR' => 'Dsnapr',
            'RANUL' => 'Ranul',
        ];
    }
}
