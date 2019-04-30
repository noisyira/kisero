<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "MO".
 *
 * @property integer $KODMO
 * @property string $KODOKATO
 * @property string $NAMMO
 * @property string $ADRESMO
 * @property integer $mo_type
 */
class MO extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MO';
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
            [['KODMO'], 'required'],
            [['KODMO', 'mo_type'], 'integer'],
            [['KODOKATO', 'NAMMO', 'ADRESMO'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'KODMO' => 'Kodmo',
            'KODOKATO' => 'Kodokato',
            'NAMMO' => 'Nammo',
            'ADRESMO' => 'Adresmo',
            'mo_type' => 'Mo Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOkato()
    {
        return $this->hasOne(RefFilial::className(), ['OKATO' => 'KODOKATO']);
    }


    public static function getOptionsbyProvince($province_id) {

        $data = static::find()
            ->select(['KODMO as id',"CONCAT(NAMMO, ' <br> <small>', ADRESMO, '</small>')  as name"])
            ->where(['KODOKATO' => $province_id])
            ->orderBy(['KODMO'=>SORT_ASC])
            ->asArray()->all();

//        if(!$province_id)
//        {
//            $data = static::find()
//                ->select(['KODMO as id',"CONCAT(NAMMO, ' <br> <small>', ADRESMO, '</small>')  as name"])
//                ->orderBy(['KODMO'=>SORT_ASC])
//                ->asArray()->all();
//        }

        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }

    public static function getMO($okato = null)
    {
        $mo = self::find()->where(['KODOKATO' => $okato])->all();
        $result = ArrayHelper::map($mo, 'KODMO', function($data){
            return $data->NAMMO .' <br><small> '.$data->ADRESMO .'</small>';
        });

        return $result;
    }

    public static function getName($code_mo)
    {
        return self::findOne($code_mo)->NAMMO;
    }
}
