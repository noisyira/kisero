<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mn_statement".
 *
 * @property string $group_statement
 * @property string $theme_statement
 * @property string $key_statement
 * @property string $gp
 * @property string $k
 */
class MnStatement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_statement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_statement', 'theme_statement', 'key_statement'], 'required'],
            [['group_statement', 'theme_statement', 'key_statement', 'gp', 'k'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_statement' => 'Group Statement',
            'theme_statement' => 'Theme Statement',
            'key_statement' => 'Key Statement',
            'gp' => 'Key Statement',
            'k' => 'K',
        ];
    }

    /**
     * Группы тем для обращения
     */
    public static function getGroupStatement()
    {
        return [
            '1' => 'Жалоба',
            '2' => 'Консультации',
            '3' => 'Заявление (письменное)',
            '4' => 'Предложение',
        ];
    }

    public static function getSearchTheme()
    {
        return ArrayHelper::map(self::find()->orderBy('k')->all(), 'key_statement', function($data){
            return  $data->k .' — '. $data->theme_statement;
        });
    }

    public static function getOptionsbyProvince($province_id) {

        $data = static::find()->where(['group_statement'=>$province_id])
            ->select(['key_statement as id','theme_statement as name'])
            ->orderBy(['key_statement'=>SORT_ASC])
            ->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }

    /**
     * Группировка обращений
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(MnGroupStatement::className(), ['id' => 'group_statement']);
    }
}
