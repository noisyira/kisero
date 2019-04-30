<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "PacientKISERO".
 *
 * @property integer $IDZAP
 * @property string $NNAPR
 * @property string $DNAPR
 * @property string $DPGOSP
 * @property string $DNGOSP
 * @property string $DOGOSP
 * @property string $DANUL
 * @property string $DPOGOSP
 * @property integer $PANUL
 * @property integer $IANUL
 * @property integer $KANUL
 * @property integer $PMOANUL
 * @property string $FAM
 * @property string $IM
 * @property string $OT
 * @property string $sex
 * @property string $DR
 * @property string $TEL
 * @property integer $VPOLIS
 * @property string $SPOLIS
 * @property string $NPOLIS
 * @property string $SMO
 * @property string $DS
 * @property string $NKART
 * @property string $DSPO
 * @property integer $FOMP
 * @property string $NAMPK
 * @property string $NAMPO
 * @property string $NAMPMO_hospital
 * @property string $NAMMO_hospital
 * @property string $NAMPMO_clinic
 * @property string $NAMMO_clinic
 * @property integer $age
 * @property string $DSNAPR
 * @property string $proxy_fam
 * @property string $proxy_im
 * @property string $proxy_ot
 * @property string $proxy_dr
 * @property integer $proxy_p
 * @property string $RANUL
 * @property string $medrab_fam
 * @property string $medrab_im
 * @property string $medrab_ot
 */
class PacientKISERO extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PacientKISERO';
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
            [['IDZAP'], 'required'],
            [['IDZAP', 'PANUL', 'IANUL', 'KANUL', 'PMOANUL', 'VPOLIS', 'FOMP', 'age', 'proxy_p'], 'integer'],
            [['NNAPR', 'DNAPR', 'DPGOSP', 'DNGOSP', 'DOGOSP', 'DANUL', 'DPOGOSP', 'FAM', 'IM', 'OT', 'sex', 'DR', 'TEL', 'SPOLIS', 'NPOLIS', 'SMO', 'DS', 'NKART', 'DSPO', 'NAMPK', 'NAMPO', 'NAMPMO_hospital', 'NAMMO_hospital', 'NAMPMO_clinic', 'NAMMO_clinic', 'DSNAPR', 'proxy_fam', 'proxy_im', 'proxy_ot', 'RANUL', 'medrab_fam', 'medrab_im', 'medrab_ot'], 'string'],
            [['proxy_dr'], 'safe'],
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
            'DOGOSP' => 'Dogosp',
            'DANUL' => 'Danul',
            'DPOGOSP' => 'Dpogosp',
            'PANUL' => 'Panul',
            'IANUL' => 'Ianul',
            'KANUL' => 'Kanul',
            'PMOANUL' => 'Pmoanul',
            'FAM' => 'Fam',
            'IM' => 'Im',
            'OT' => 'Ot',
            'sex' => 'Sex',
            'DR' => 'Dr',
            'TEL' => 'Tel',
            'VPOLIS' => 'Vpolis',
            'SPOLIS' => 'Spolis',
            'NPOLIS' => 'Npolis',
            'SMO' => 'Smo',
            'DS' => 'Ds',
            'NAMMKB' => 'nammkb',
            'NAMMKBDSPO' => 'nammkbdspo',
            'NKART' => 'Nkart',
            'DSPO' => 'Dspo',
            'FOMP' => 'Fomp',
            'NAMPK' => 'Nampk',
            'NAMPO' => 'Nampo',
            'NAMPMO_hospital' => 'Nampmo Hospital',
            'NAMMO_hospital' => 'Nammo Hospital',
            'NAMPMO_clinic' => 'Nampmo Clinic',
            'NAMMO_clinic' => 'Nammo Clinic',
            'age' => 'Age',
            'DSNAPR' => 'Dsnapr',
            'proxy_fam' => 'Proxy Fam',
            'proxy_im' => 'Proxy Im',
            'proxy_ot' => 'Proxy Ot',
            'proxy_dr' => 'Proxy Dr',
            'proxy_p' => 'Proxy P',
            'RANUL' => 'Ranul',
            'medrab_fam' => 'Medrab Fam',
            'medrab_im' => 'Medrab Im',
            'medrab_ot' => 'Medrab Ot',
        ];
    }

    public function getCall() {
        return $this->hasMany(EirPeople::className(), ['nnapr' => 'NNAPR'])->from(EirPeople::tableName() . ' FBF');
    }
}
