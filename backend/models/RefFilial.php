<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ref_filial".
 *
 * @property string $CODE
 * @property string $NAME
 * @property string $EMAIL
 * @property string $NAME_RAY
 * @property string $OKATO
 * @property string $OKATO_CITY
 * @property integer $PR_GOR
 * @property string $ZAGS
 */
class RefFilial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_filial';
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
            [['CODE', 'NAME', 'EMAIL', 'NAME_RAY', 'OKATO', 'OKATO_CITY'], 'required'],
            [['CODE', 'NAME', 'EMAIL', 'NAME_RAY', 'OKATO', 'OKATO_CITY', 'ZAGS'], 'string'],
            [['PR_GOR'], 'integer'],
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
            'EMAIL' => 'Email',
            'NAME_RAY' => 'Name  Ray',
            'OKATO' => 'Okato',
            'OKATO_CITY' => 'Okato  City',
            'PR_GOR' => 'Pr  Gor',
            'ZAGS' => 'Zags',
        ];
    }
}