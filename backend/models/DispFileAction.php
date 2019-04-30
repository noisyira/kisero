<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "disp_file_action".
 *
 * @property integer $id
 * @property integer $action_type
 * @property string $action_date
 * @property string $action_comment
 * @property integer $dfpi
 */
class DispFileAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'disp_file_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_type', 'dfpi'], 'required'],
            [['action_type', 'dfpi'], 'integer'],
            [['action_date'], 'safe'],
            [['action_comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_type' => 'Action Type',
            'action_date' => 'Action Date',
            'action_comment' => 'Action Comment',
            'dfpi' => 'Dfpi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValue()
    {
        return $this->hasOne(MnDispFileAction::className(), ['code' => 'action_type']);
    }
}