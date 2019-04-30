<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mn_theme_roszdravnadzor".
 *
 * @property integer $id
 * @property string $theme
 *
 * @property StmtRoszdravnadzor[] $stmtRoszdravnadzors
 */
class MnThemeRoszdravnadzor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mn_theme_roszdravnadzor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme'], 'required'],
            [['theme'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'theme' => 'Тема вопроса',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStmtRoszdravnadzors()
    {
        return $this->hasMany(StmtRoszdravnadzor::className(), ['theme' => 'id']);
    }
}
