<?php

namespace backend\models;

use app\models\PollQuestion;
use Yii;

/**
 * This is the model class for table "pollView".
 *
 * @property integer $id
 * @property string $company
 * @property integer $user_o
 * @property string $description
 * @property integer $status
 * @property string $name
 * @property string $value
 * @property string $addressLive
 * @property string $NAMMO
 * @property string $ADRESMO
 */
class PollView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pollView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'required'],
            [['id', 'user_o', 'status'], 'integer'],
            [['company', 'description', 'name', 'value', 'addressLive', 'NAMMO', 'ADRESMO'], 'string'],
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
            'user_o' => 'User O',
            'description' => 'Description',
            'status' => 'Status',
            'name' => 'Name',
            'value' => 'Value',
            'addressLive' => 'Address Live',
            'NAMMO' => 'Nammo',
            'ADRESMO' => 'Adresmo',
        ];
    }

    /**
     * Вопросы
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasMany(PollQuestion::className(), ['poll_id' => 'poll_key'])
            ->where(['type' => 'q']);
    }
}
