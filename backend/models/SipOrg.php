<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sipOrg".
 *
 * @property integer $sip
 * @property integer $org
 * @property string $name
 */
class SipOrg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sipOrg';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('useroptions');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sip'], 'required'],
            [['sip', 'org'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sip'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sip' => 'Sip',
            'org' => 'Org',
            'name' => 'Name',
        ];
    }
}
