<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stmt_deffered".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property string $id_erz
 * @property string $name_okato
 * @property string $req_okato
 * @property string $fam
 * @property string $im
 * @property string $ot
 * @property string $dt
 * @property string $phone
 * @property string $add_fio
 * @property string $add_phone
 * @property string $email
 * @property string $description
 * @property integer $active
 */
class StmtDeffered extends \yii\db\ActiveRecord
{
    public $residence;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_deffered';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id'], 'required'],
            [['stmt_id', 'active'], 'integer'],
            [['id_erz', 'name_okato', 'req_okato', 'fam', 'im', 'ot', 'phone', 'add_fio', 'add_phone', 'email', 'description'], 'string'],
            [['dt'], 'safe'],
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
            'id_erz' => 'Id Erz',
            'name_okato' => 'Место обращения',
            'req_okato' => 'Место обращения',
            'fam' => 'Фамилия',
            'im' => 'Имя',
            'ot' => 'Отчество',
            'dt' => 'Дата рождения',
            'residence' => 'Адрес',
            'phone' => 'Контактный телефон',
            'add_fio' => 'Доп.: контактное лицо',
            'add_phone' => 'Доп.: контактный телефон',
            'email' => 'E-mail',
            'description' => 'Комментарий',
            'active' => 'Отсроченный ответ',
        ];
    }

    /**
     * Информация "Отсроченный ответ"
     * @return \yii\db\ActiveQuery
     */
    public function getDeffered()
    {
        return $this->hasOne(Stmt::className(), ['id' => 'stmt_id']);
//            ->where(['active' => 1]);
    }

    /**
     * Информация по обращению
     * @return \yii\db\ActiveQuery
     */
    public function getStmt()
    {
        return $this->hasOne(Stmt::className(), ['id' => 'stmt_id'])
            ->with(['group', 'theme', 'result']);
    }

    public function createDefferedStmt($id, $data)
    {
        $defer = new StmtDeffered();
        $date =  new \DateTime();

        $defer->stmt_id = $id;
        $defer->id_erz = isset($data->enp)?$data->enp:null;
        $defer->fam = $data->fam;
        $defer->im = $data->im;
        $defer->ot = $data->ot;
        if($data->okato_erz){
            $defer->req_okato = $data->okato;
            $defer->name_okato = $data->okato_name;
        }else{
            $defer->req_okato = isset($data->okato->id)?$data->okato->id:'';
            $defer->name_okato = isset($data->okato->text)?$data->okato->text:'';
        }

        if($data->dt)
        {
            $defer->dt = Yii::$app->formatter->asDate($data->dt);
        }

        $defer->phone = isset($data->phone)?$data->phone:null;
        $defer->add_fio = isset($data->add_fio)?$data->add_fio:null;
        $defer->add_phone = isset($data->add_phone)?$data->add_phone:null;
        $defer->email = isset($data->email)?$data->email:null;
        $defer->description = isset($data->desc)?$data->desc:'';
        $defer->active = 0;

        $defer->save();
    }

    /**
     * Убрать у обращения статус отсроченный ответ
     * @param $id
     * @throws \Exception
     */
    public static function closeDeffered($id)
    {
        $model = self::find()->where(['stmt_id' => $id])->one();

        if($model)
            $model->active = 0;

        $model->update();
    }

    public static function updateCall($id, $data)
    {
        $defer = new StmtDeffered();
        $date =  new \DateTime($data->dt);

        $model = $defer->find()->where(['stmt_id' => $id])->one();
        if($model){
            $model->id_erz = isset($data->enp)?$data->enp:null;
            $model->fam = $data->fam;
            $model->im = $data->im;
            $model->ot = $data->ot;
            if($data->okato_erz){
                $model->req_okato = $data->okato;
                $model->name_okato = $data->okato_name;
            }else{
                $model->req_okato = $data->okato->id;
                $model->name_okato = $data->okato->text;
            }

            $defer->phone = $data->phone;
            $model->dt = isset($data->dt)?Yii::$app->formatter->asDate($date->add(new \DateInterval('P1D'))->format('d-m-Y')):null;
            $model->add_fio = isset($data->add_fio)?$data->add_fio:null;
            $model->add_phone = isset($data->add_phone)?$data->add_phone:null;

            $model->update();
        }else {
            $defer->stmt_id = $id;

            $defer->id_erz = isset($data->enp)?$data->enp:null;
            $defer->fam = $data->fam;
            $defer->im = $data->im;
            $defer->ot = $data->ot;
            if($data->okato_erz){
                $defer->req_okato = $data->okato;
                $defer->name_okato = $data->okato_name;
            }else{
                $defer->req_okato = $data->okato->id;
                $defer->name_okato = $data->okato->text;
            }
            $defer->dt = isset($data->dt)?Yii::$app->formatter->asDate($date->add(new \DateInterval('P1D'))->format('d-m-Y')):null;

            $defer->phone = $data->phone;
            $defer->add_fio = isset($data->add_fio)?$data->add_fio:null;
            $defer->add_phone = isset($data->add_phone)?$data->add_phone:null;
            $defer->email = $data->email;
            $defer->description = $data->desc;
            $defer->active = !empty($data->def)?1:0;

            $defer->save();
        }
    }

}
