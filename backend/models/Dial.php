<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dial".
 *
 * @property integer $id
 * @property string $Name
 * @property string $sName
 * @property string $pName
 * @property string $dateMan
 * @property integer $sexMan
 * @property string $contact
 * @property string $addressLive
 * @property integer $pd
 * @property integer $smo
 * @property string $NAMMO
 * @property string $ADRESMO
 */
class Dial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial';
    }

    public static  function  primaryKey()
    {
        return 'id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'Name', 'sName', 'dateMan', 'sexMan', 'smo'], 'required'],
            [['id', 'sexMan', 'pd', 'smo'], 'integer'],
            [['Name', 'sName', 'pName', 'contact', 'addressLive', 'NAMMO', 'ADRESMO'], 'string'],
            [['dateMan'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Name' => 'Name',
            'sName' => 'S Name',
            'pName' => 'P Name',
            'dateMan' => 'Date Man',
            'sexMan' => 'Sex Man',
            'contact' => 'Contact',
            'addressLive' => 'Address Live',
            'pd' => 'Pd',
            'smo' => 'Smo',
            'NAMMO' => 'Nammo',
            'ADRESMO' => 'Adresmo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasOne(DialPeople::className(), ['pid' => 'id'])
            ->with(['operator']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswerlist()
    {
        return $this->hasMany(DialAnswers::className(), ['people_id' => 'id']);
    }

    /**
     */
    public static function setDialStatus($id)
    {
        $model = self::findOne(['id' => $id]);

        if(!$model)
            return 'false';

        return $model;
    }
}