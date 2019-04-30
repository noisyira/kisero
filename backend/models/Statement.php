<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "statement".
 *
 * @property integer $id
 * @property string $channel_id
 * @property integer $user_id
 * @property integer $send_user
 * @property integer $statement
 * @property string $statement_date
 * @property string $tip_statement
 * @property string $theme_statement
 * @property string $theme_statement_description
 * @property integer $erz_uid
 * @property string $f_name
 * @property string $name
 * @property string $l_name
 * @property string $dt
 * @property integer $def_answer
 * @property string $contact_phone
 * @property string $status
 */
class Statement extends \yii\db\ActiveRecord
{
    public $rez;
    public $fio;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_id', 'user_id', 'statement', 'statement_date', 'tip_statement'], 'required'],
            [['tip_statement', 'theme_statement', 'theme_statement_description', 'f_name', 'name', 'l_name', 'contact_phone', 'status'], 'string'],
            [['user_id', 'send_user', 'statement', 'erz_uid', 'def_answer'], 'integer'],
            [['statement_date', 'dt'], 'safe']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'user_id' => 'User ID',
            'send_user' => 'Send User',
            'statement' => 'Statement',
            'statement_date' => 'Statement Date',
            'tip_statement' => 'Tip Statement',
            'theme_statement' => 'Theme Statement',
            'theme_statement_description' => 'Theme Statement Description',
            'erz_uid' => 'Erz Uid',
            'f_name' => 'F Name',
            'name' => 'Name',
            'l_name' => 'L Name',
            'dt' => 'Dt',
            'def_answer' => 'Def Answer',
            'contact_phone' => 'Contact Phone',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(MnGroupStatement::className(), ['id' => 'tip_statement']);
    }

    public function getTheme()
    {
        return $this->hasOne(MnStatement::className(), ['key_statement' => 'theme_statement']);
    }

    public function getSend()
    {
        return $this->hasOne(MnSendStatement::className(), ['id' => 'statement']);
    }

    public function getUser()
    {
        return $this->hasOne(Login::className(), ['id' => 'user_id']);
    }

    public function getAction()
    {
        return $this->hasOne(StatementAction::className(), ['channel_id' => 'channel_id'])->orderBy(['dt' => SORT_DESC]);
    }

    public function getResult()
    {
        return $this->hasOne(MnResultStatement::className(), ['id' => 'accept']);
    }
}
