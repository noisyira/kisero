<?php

namespace backend\models;

use app\models\PollQuestion;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "poll_answers".
 *
 * @property integer $id
 * @property integer $poll_id
 * @property integer $people_id
 * @property string $question_key
 * @property string $answer_key
 */
class PollAnswers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['poll_id', 'people_id'], 'required'],
            [['poll_id', 'people_id'], 'integer'],
            [['question_key', 'answer_key'], 'string'],
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
            'people_id' => 'People ID',
            'question_key' => 'Question Key',
            'answer_key' => 'Answer Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(PollQuestion::className(), ['id' => 'question_key']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasOne(PollPeople::className(), ['id' => 'people_id']);
    }

    /**
     * @param $id
     * @param bool $c
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function listAnswers($id, $c = false)
    {
        $query = self::find();
        $query->alias('pa');
        $query->select(['pa.question_key', 'pa.answer_key', 'COUNT(pa.id) as count']);
        $query->joinWith(['people', 'question']);
        if($c)
        {
            $query->where(['pa.poll_id' => $id]);
        }
        $query->andWhere(['poll_people.company' => 3]);
        $query->groupBy(['pa.question_key', 'pa.answer_key']);

        return $query->asArray()->all();
    }

    /**
     * @param $id
     * @return array
     */
    public static function listC($id)
    {
        $c = PollPeople::find()->where(['poll_id' => $id])->all();

        return ArrayHelper::map($c, 'company', 'company');
    }


    /**
     * @param $poll_id
     * @param $question
     * @param $answer
     * @return int|string
     */
    public static function listCount($poll_id, $question, $answer, $c)
    {
        return self::find()
            ->alias('pa')
            ->joinWith(['people'])
            ->where(['pa.poll_id' => $poll_id, 'pa.question_key' => $question, 'pa.answer_key' => $answer])
            ->andWhere(['poll_people.company' => $c])
            ->count();
    }

    /**
     * @param $id
     * @param bool $c
     * @return int|string
     */
    public static function listAllCount($id, $c = false)
    {
        return self::find()
            ->alias('pa')
            ->select(['pa.people_id'])
            ->joinWith(['people'])
            ->where(['pa.poll_id' => $id])
            ->andWhere(['poll_people.company' => $c])
            ->groupBy(['pa.people_id'])
            ->count();
    }
}
