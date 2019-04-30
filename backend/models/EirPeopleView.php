<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "eirPeopleView".
 *
 * @property integer $id
 * @property string $params
 * @property string $nnapr
 * @property integer $result
 * @property string $panul
 * @property string $comment
 * @property integer $another_phone
 * @property integer $user_id
 * @property string $insdate
 * @property string $username
 * @property integer $company
 * @property integer $sub_company
 * @property string $subdivision
 * @property string $role
 * @property integer $role_type
 * @property string $fam
 * @property string $im
 * @property string $ot
 * @property integer $block
 * @property string $name
 * @property integer $code_smo
 */
class EirPeopleView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eirPeopleView';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'username', 'company', 'name'], 'required'],
            [['id', 'result', 'another_phone', 'user_id', 'company', 'sub_company', 'role_type', 'block', 'code_smo'], 'integer'],
            [['params', 'nnapr', 'panul', 'comment', 'username', 'subdivision', 'role', 'fam', 'im', 'ot', 'name'], 'string'],
            [['insdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'params' => 'Params',
            'nnapr' => 'Nnapr',
            'result' => 'Result',
            'panul' => 'Panul',
            'comment' => 'Comment',
            'another_phone' => 'Another Phone',
            'user_id' => 'User ID',
            'insdate' => 'Insdate',
            'username' => 'Username',
            'company' => 'Company',
            'sub_company' => 'Sub Company',
            'subdivision' => 'Subdivision',
            'role' => 'Role',
            'role_type' => 'Role Type',
            'fam' => 'Fam',
            'im' => 'Im',
            'ot' => 'Ot',
            'block' => 'Block',
            'name' => 'Name',
            'code_smo' => 'Code Smo',
        ];
    }

}
