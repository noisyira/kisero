<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smo".
 *
 * @property string $CODE
 * @property string $NAME
 * @property string $OLD_COD
 * @property string $INN
 * @property string $OGRN
 * @property string $KPP
 * @property string $OKPO
 * @property string $STATUS
 * @property integer $id
 * @property integer $Kod_smo
 * @property resource $address
 * @property string $addresss
 * @property string $FNAME
 *
 * @property Reestr[] $reestrs
 */
class Smo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smo';
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
            [['CODE', 'NAME', 'OLD_COD', 'STATUS'], 'required'],
            [['CODE', 'NAME', 'OLD_COD', 'INN', 'OGRN', 'KPP', 'OKPO', 'STATUS', 'address', 'addresss', 'FNAME'], 'string'],
            [['Kod_smo'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CODE' => 'Code',
            'NAME' => 'Name',
            'OLD_COD' => 'Old  Cod',
            'INN' => 'Inn',
            'OGRN' => 'Ogrn',
            'KPP' => 'Kpp',
            'OKPO' => 'Okpo',
            'STATUS' => 'Status',
            'id' => 'ID',
            'Kod_smo' => 'Kod Smo',
            'address' => 'Address',
            'addresss' => 'Addresss',
            'FNAME' => 'Fname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReestrs()
    {
        return $this->hasMany(Reestr::className(), ['orgId' => 'id']);
    }
}
