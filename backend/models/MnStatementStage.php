<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mn_statement_stage".
 *
 * @property integer $id
 * @property string $stage
 */
class MnStatementStage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_statement_stage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stage'], 'required'],
            [['stage'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stage' => 'Stage',
        ];
    }
}
