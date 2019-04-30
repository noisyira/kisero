<?php

namespace backend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stmt_action".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property string $action_date
 * @property integer $user_id
 * @property string $action
 * @property string $description
 */
class StmtAction extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_action';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['action_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'action_date',
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id', 'action_date', 'user_id'], 'required'],
            [['stmt_id', 'user_id', 'action'], 'integer'],
            [['action_date'], 'safe'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stmt_id' => 'Stmt ID',
            'action_date' => 'Action Date',
            'user_id' => 'User ID',
            'action' => 'Action',
            'description' => 'Description',
        ];
    }

    public static function createAction($id, $user, $act, $desc = null)
    {
        $action = new StmtAction();
        $date =  new \DateTime();

        $action->stmt_id = $id;
        $action->action_date =  Yii::$app->formatter->asDatetime($date);
        $action->user_id = $user;
        $action->action = $act;
        $action->description = $desc;
        
        $action->save();
    }

    /**
     * Информация "Название действия"
     * @return \yii\db\ActiveQuery
     */
    public function getAction_name()
    {
        return $this->hasOne(MnStatementAction::className(), ['id' => 'action']);
    }

    /**
     * Информация "Пользователь"
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Login::className(), ['id' => 'user_id']);
    }

    /**
     * Логирование действий
     * @param $action_id
     * @param $model
     * @param null $desc
     */
    public static function setAction($action_id, $model, $desc = null)
    {
        $action = new StmtAction();
        $action->user_id = Yii::$app->user->id;
        $action->action = $action_id;
        $action->description = $desc;
        $model->link('action', $action);
    }
}
