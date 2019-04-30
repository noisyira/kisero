<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stmt_collection".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property integer $result
 * @property string $cash
 */
class StmtCollection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id', 'result'], 'required'],
            [['stmt_id', 'result'], 'integer'],
            [['cash'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stmt_id' => 'Stmt ID',
            'result' => 'Result',
            'cash' => 'Cash',
        ];
    }
}
