<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mn_company_sub".
 *
 * @property integer $id
 * @property integer $company
 * @property string $name
 */
class MnCompanySub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_company_sub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company', 'name'], 'required'],
            [['company'], 'integer'],
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
            'company' => 'Company',
            'name' => 'Name',
        ];
    }

    /**
     * список филиалов и доп. офисов
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getSubCompany($id) {

        $data = MnCompanySub::find()->where(['company'=>$id])
            ->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
