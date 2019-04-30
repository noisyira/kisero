<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mn_disp_file_action".
 *
 * @property integer $code
 * @property string $label
 */
class MnDispFileAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_disp_file_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'label'], 'required'],
            [['code'], 'integer'],
            [['label'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'label' => 'Label',
        ];
    }
}
