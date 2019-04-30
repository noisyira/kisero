<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "poll".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property integer $activity
 */
class Poll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'string'],
            [['activity'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'name' => 'Name',
            'activity' => 'Activity',
        ];
    }

    public static function getOptions(){
        $data = static::find()->where(['activity' => 1])->all();
        $value = (count($data)==0) ? [''=>''] : ArrayHelper::map($data, 'key','name');

        return $value;
    }

    /**
     * @param $key
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getNamePoll($key){
        return self::find()->where(['key' => $key])->one();
    }
}
