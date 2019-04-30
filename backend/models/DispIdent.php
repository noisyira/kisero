<?php

namespace app\models;

use backend\models\People;
use Yii;

/**
 * This is the model class for table "disp_ident".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $pid
 * @property integer $smo
 *
 * @property DispContent $c
 */
class DispIdent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'disp_ident';
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
            [['cid', 'pid', 'smo'], 'required'],
            [['cid', 'pid', 'smo'], 'integer'],
            [['cid'], 'exist', 'skipOnError' => true, 'targetClass' => DispContent::className(), 'targetAttribute' => ['cid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'pid' => 'Pid',
            'smo' => 'Smo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(DispContent::className(), ['id' => 'cid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasOne(People::className(), ['id' => 'pid']);
    }

    public function getSmo()
    {
        return $this->hasOne(Smo::className(), ['Kod_smo' => 'smo']);
    }
}
