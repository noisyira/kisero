<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mn_company".
 *
 * @property integer $id
 * @property string $name
 */
class MnCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_company';
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

    public static function getOrg()
    {
        $company = Login::find()
            ->where(['id' => Yii::$app->user->id])
            ->with(['org'])
            ->one();

        return $company;
    }

    /**
     * @param $id
     * @return null|static
     */
    public static function getNameSMO($id)
    {
        return self::findOne($id);
    }
}
