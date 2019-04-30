<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stmt_attachment".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property string $n_attach
 * @property string $date_attach
 * @property string $file_name
 * @property string $file_type
 * @property string $path
 * @property string $dt
 */
class StmtAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id', 'file_name', 'file_type', 'path', 'dt'], 'required'],
            [['stmt_id'], 'integer'],
            [['n_attach', 'file_name', 'file_type', 'path'], 'string'],
            [['date_attach', 'dt'], 'safe'],
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
            'n_attach' => 'N Attach',
            'date_attach' => 'Date Attach',
            'file_name' => 'File Name',
            'file_type' => 'File Type',
            'path' => 'Path',
            'dt' => 'Dt',
        ];
    }

    public static function createAttch($id_stmt, $name, $type, $path, $data = false)
    {
        $model = new StmtAttachment();

        $date =  new \DateTime();

        $dateAttach = ($data['date'] != 'null') ? $data['date'] : $date;
        $dateAttach = ($data['date'] != "") ? $dateAttach : $date;

        $model->stmt_id = $id_stmt;
        $model->dt = Yii::$app->formatter->asDatetime($date);
        $model->file_name = $name;
        $model->file_type = $type;
        $model->path = $path;
        $model->n_attach = $data['num'];
        $model->date_attach =  Yii::$app->formatter->asDate($dateAttach);

        $isFile = self::find()->where(['path' => $path, 'file_name' => $name])->one();

       if(!$isFile && $model->save())
       {
           return true;
       }

        return false;
    }
}
