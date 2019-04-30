<?php

namespace app\models;

use backend\models\Stmt;
use Yii;

/**
 * This is the model class for table "stmt_relate".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property integer $related_id
 */
class StmtRelate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_relate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id', 'related_id'], 'required'],
            [['stmt_id', 'related_id'], 'integer'],
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
            'related_id' => 'Related ID',
        ];
    }

    /**
     * Информация "Обращение"
     * @return \yii\db\ActiveQuery
     */
    public function getStmt_rel()
    {
        return $this->hasOne(Stmt::className(), ['id' => 'related_id'])
            ->with(['group', 'theme', 'stmt_status', 'deffered', 'user']);
    }
}
