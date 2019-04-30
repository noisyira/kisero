<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "disp_files".
 *
 * @property integer $id
 * @property string $filename
 * @property string $mo
 * @property integer $y
 * @property integer $n
 * @property string $result
 * @property string $insdate
 *
 * @property DispContent[] $dispContents
 * @property DispFlk[] $dispFlks
 * @property DispZglv[] $dispZglvs
 */
class DispFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'disp_files';
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
            [['filename'], 'required'],
            [['filename', 'mo', 'result'], 'string'],
            [['y', 'n'], 'integer'],
            [['insdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'mo' => 'Mo',
            'y' => 'Y',
            'n' => 'N',
            'result' => 'Result',
            'insdate' => 'Insdate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispContents()
    {
        return $this->hasMany(DispContent::className(), ['fid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispFlks()
    {
        return $this->hasMany(DispFlk::className(), ['fid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispZglvs()
    {
        return $this->hasMany(DispZglv::className(), ['fid' => 'id']);
    }
}
