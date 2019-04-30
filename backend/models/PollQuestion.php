<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "poll_question".
 *
 * @property integer $id
 * @property integer $poll_id
 * @property integer $order_list
 * @property string $type
 * @property string $value
 */
class PollQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['poll_id', 'order_list'], 'integer'],
            [['order_list'], 'required'],
            [['type', 'value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poll_id' => 'Poll ID',
            'order_list' => 'Order List',
            'type' => 'Type',
            'value' => 'Value',
        ];
    }

    public static function answerName($id)
    {
        switch ($id) {
            case 1:
                return "Да";
                break;
            case 2:
                return "Нет";
                break;
            case 3:
                return "Не знаю";
                break;
            default:
                return "";
        }
    }
}
