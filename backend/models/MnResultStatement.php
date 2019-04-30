<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mn_result_statement".
 *
 * @property integer $id
 * @property string $name
 */
class MnResultStatement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_result_statement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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

    public static function getName($id)
    {
        $name = MnResultStatement::find()->select('name')->where(['id' => $id])->one();

        return $name;
    }

    public static function getOptions()
    {
        $data=  static::find()->all();
        $value=(count($data)==0)? [''=>'']: ArrayHelper::map($data, 'id','name');

        return $value;
    }
}
