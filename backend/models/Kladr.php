<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kladr".
 *
 * @property integer $ID
 * @property integer $CC
 * @property integer $AREAID
 * @property integer $CITYID
 * @property integer $PUNKTID
 * @property integer $STREETID
 * @property string $NAME
 * @property string $SOCR
 * @property integer $LEVELID
 * @property string $CODEKLADR
 * @property integer $ACTUAL
 * @property string $INDEXKLADR
 * @property string $OKATO
 */
class Kladr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kladr';
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
            [['CC', 'AREAID', 'CITYID', 'PUNKTID', 'STREETID', 'LEVELID', 'ACTUAL'], 'integer'],
            [['NAME', 'SOCR', 'CODEKLADR', 'INDEXKLADR', 'OKATO'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'CC' => 'Cc',
            'AREAID' => 'Areaid',
            'CITYID' => 'Cityid',
            'PUNKTID' => 'Punktid',
            'STREETID' => 'Streetid',
            'NAME' => 'Name',
            'SOCR' => 'Socr',
            'LEVELID' => 'Levelid',
            'CODEKLADR' => 'Codekladr',
            'ACTUAL' => 'Actual',
            'INDEXKLADR' => 'Indexkladr',
            'OKATO' => 'Okato',
        ];
    }

    public static function getName($q)
    {
        $model = Kladr::find()
            ->select(['OKATO as id','NAME as text', 'SOCR'
            ])
            ->where(['LIKE', 'NAME', 'Став'])
            ->andWhere(['IN', 'LEVELID', [3,4]])
            ->limit(20)
            ->asArray()
            ->all();

            $out['results'] = ArrayHelper::map($model, 'id', function($data, $default){
            return $data;
        });
    }

    public static function getOkato($okato)
    {
        $model =  Kladr::find()

            ->where(['OKATO' => $okato])
            ->andWhere(['IN', 'LEVELID', [1,2,3]])
            ->andWhere(['CC' => 26 ])
            ->all();

        return $model;
    }
}
