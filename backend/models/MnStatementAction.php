<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mn_statement_action".
 *
 * @property integer $id
 * @property string $name
 */
class MnStatementAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_statement_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Название действия по имени
     * @param $id
     * @return string
     */
    public static function getNameAction($id)
    {
        $name = self::findOne($id);

        return $name->name;
    }
}
