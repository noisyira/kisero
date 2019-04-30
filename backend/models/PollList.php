<?php

namespace backend\models;

use app\models\Poll;
use app\models\PollQuestion;
use Yii;

/**
 * This is the model class for table "poll_list".
 *
 * @property integer $id
 * @property string $poll_key
 * @property string $poll_start
 * @property string $poll_end
 * @property string $description
 * @property string $status
 */
class PollList extends \yii\db\ActiveRecord
{
    public $result;
    public $tfoms;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['poll_key', 'description'], 'required'],
            [['poll_key', 'description', 'status'], 'string'],
            [['tfoms'], 'integer'],
            [['poll_start', 'poll_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poll_key' => 'Типовой сценарий',
            'poll_start' => 'Начало',
            'poll_end' => 'Завершение',
            'description' => 'Описание',
            'status' => 'Status',
            'tfoms' => 'Внутренний опрос ТФОМС СК'
        ];
    }

    /**
     * Текущий опрос
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(PollPeople::className(), ['poll_id' => 'id']);
    }

    /**
     * название опроса
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(Poll::className(), ['key' => 'poll_key']);
    }

    public function getCheck()
    {
        return $this->hasMany(PollPeople::className(), ['poll_id' => 'id']);
    }

    public function getList()
    {
        return $this->hasMany(PollQuestion::className(), ['poll_id' => 'poll_key']);
    }

}
