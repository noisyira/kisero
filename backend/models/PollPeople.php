<?php

namespace backend\models;

use app\models\PollQuestion;
use app\models\PollResult;
use Yii;

/**
 * This is the model class for table "poll_people".
 *
 * @property integer $id
 * @property string $enp
 * @property integer $company
 * @property integer $user_o
 * @property string $description
 * @property integer $status
 * @property integer $poll_id
 */
class PollPeople extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_people';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enp', 'poll_id'], 'required'],
            [['enp', 'description'], 'string'],
            [['company', 'user_o', 'status', 'poll_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'enp' => 'Enp',
            'company' => 'Company',
            'user_o' => 'User O',
            'description' => 'Description',
            'status' => 'Status',
            'poll_id' => 'Poll Key',
        ];
    }

    public static function getPollkey($id)
    {
        $model = PollPeople::findOne($id);

        if($model)
        {
            $list = PollList::findOne($model->poll_id);

            return $list->poll_key;
        }

        return false;
    }

    /**
     * Текущий опрос
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(PollList::className(), ['id' => 'poll_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(MnCompany::className(), ['id' => 'company']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(Login::className(), ['id' => 'user_o']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInsured()
    {
        return $this->hasOne(People::className(), ['ENP' => 'enp'])
            ->with(['stik', 'reestr']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasOne(PollList::className(), ['id' => 'poll_id'])
            ->with('list');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(PollAnswers::className(), ['people_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult(){
        return $this->hasOne(PollResult::className(), ['id' => 'status']);
    }

    public function getAnswers(){
        return $this->hasMany(PollAnswers::className(), ['people_id' => 'id'])
//            ->select(['question_key', 'answer_key', 'COUNT(people_id) as count'])
//            ->groupBy(['question_key', 'answer_key'])
            ;
    }

    /**
     * @param $id
     */
    public static function setStatus($id)
    {
        $model = self::findOne(['id' => $id]);

        if($model->status == 0)
        {
            $model->user_o = Yii::$app->user->id;
            $model->status = 12;
            $model->update();
        }
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function reportCompany($id)
    {
        $poll = self::find()
            ->select(['status', 'company', 'COUNT(poll_people.id) as count'])
            ->joinWith(['result', 'org'])
            ->where(['poll_id' => $id])
            ->groupBy(['status', 'company'])
            ->asArray()
            ->all();

        return $poll;
    }
}