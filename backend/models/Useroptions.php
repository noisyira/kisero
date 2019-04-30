<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "useroptions".
 *
 * @property integer $extension
 * @property string $resource
 */
class Useroptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'useroptions';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('useroptions');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['extension', 'resource'], 'required'],
            [['extension'], 'integer'],
            [['resource'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'extension' => 'Extension',
            'resource' => 'Resource',
        ];
    }

    public static function createOptions($user)
    {
        $model = new Useroptions();

        $model->extension = $user;
        $model->resource = "PJSIP/".$user;

        $model->save();
    }
}
