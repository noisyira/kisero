<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "disp_file".
 *
 * @property integer $id
 * @property string $file_name
 * @property string $dt
 * @property integer $user_id
 * @property integer $company
 */
class DispFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'disp_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name'], 'string'],
            [['dt'], 'required'],
            [['dt'], 'safe'],
            [['user_id', 'company'], 'integer'],
            [['file_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'dt' => 'Dt',
            'user_id' => 'User ID',
            'company' => 'Company',
        ];
    }
}