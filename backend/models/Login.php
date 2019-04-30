<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Login".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $secret
 * @property integer $company
 * @property integer $sub_company
 * @property string $subdivision
 * @property string $role
 * @property integer $role_type
 * @property string $fam
 * @property string $im
 * @property string $ot
 * @property string $auth_key
 * @property string $created
 * @property string $updated
 * @property integer $block
 */
class Login extends \yii\db\ActiveRecord
{
    public $type;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'company'], 'required'],
            [['username', 'password', 'secret', 'subdivision', 'type', 'role', 'fam', 'im', 'ot', 'auth_key'], 'string'],
            [['company', 'sub_company', 'role_type', 'block'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'secret' => 'Secret',
            'company' => 'Организация',
            'sub_company' => 'Филиал или доп. офис',
            'subdivision' => 'Подразделение',
            'role' => 'Тип пользователя',
            'type' => 'Уровень доступа',
            'role_type' => 'Role Type',
            'fam' => 'Фамилия',
            'im' => 'Имя',
            'ot' => 'Отчество',
            'auth_key' => 'Auth Key',
            'created' => 'Created',
            'updated' => 'Updated',
            'block' => 'Block',
        ];
    }

    /**
     * Информация "Компания принявшая обращение"
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(MnCompany::className(), ['id' => 'company']);
    }

    /**
     * Название организации по id
     */
    public static function getCompanyUser($id)
    {
        return self::find()->where(['id' => $id])->with(['companyname'])->one();
    }

    /**
     * Информация "Пользователь"
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SipAccount::className(), ['user_id' => 'id']);
    }

    /**
     * Филиал или доп. офис
     * @return \yii\db\ActiveQuery
     */
    public function getSub()
    {
        return $this->hasOne(MnCompanySub::className(), ['id' => 'sub_company']);
    }

    /**
     * ID организации пользователя
     * @param $id
     * @return int
     */
    public static function companyID($id)
    {
        $user = self::findOne($id);

        return $user->company;
    }

    /**
     * Тип пользователя
     * @param $id
     * @return int
     */
    public static function getTypeUser($id)
    {
        $user = self::findOne($id);

        return $user->role_type;
    }


    /**
     * Назначить исполнителем
     * @return mixed
     */
    public static function responsibleUser()
    {
        $users = self::find()
            ->with(['role', 'org'])
            ->all();

        $result = ArrayHelper::map($users, 'id', function ($data) {
            $info = $data->fam . ' ' . $data->im . ' ' . $data->ot . '<br>' .
                '<small>' .
                $data->org->name . ' - ' . $data->role
                . '</small>';
            return $info;
        });

        return $result;
    }

    public static function userList()
    {
        $users = self::find();
        $users->where(['company' => self::companyID(Yii::$app->user->id)]);

        return $users->asArray()->all();
    }

    /**
     * Роль пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * Название организации
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(MnCompany::className(), ['id' => 'company']);
    }

    /**
     * Название организации
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyname()
    {
        return $this->hasOne(MnCompany::className(), ['id' => 'company']);
    }


    public function getReportdial()
    {
        return $this->hasMany(DialPeople::className(), ['user_o' => 'id']);
    }

    /**
     * Регистрация нового оператора
     */
    public function registration()
    {
        $date = new \DateTime();

        $model = new Login();
        $model->attributes = $this->attributes;
        $model->created = Yii::$app->formatter->asDate($date);
        $model->password = Yii::$app->security->generatePasswordHash($this->secret);

        if ($model->save())
            return $model;

        return false;
    }

    public function userPDF($id)
    {
        $login = new Login();

        $user = $login->find()->where(['id' => $id])->one();
    }

    /**
     * @param $id
     * @return int|string
     *
     */
    public static function getSmoId($id)
    {
        $model = self::findOne($id);

        switch ($model->company) {
            case 2:
                return 26002;
                break;
            case 3:
                return 26005;
                break;
            default:
                return '';
        }
    }
}
