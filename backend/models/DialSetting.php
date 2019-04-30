<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dial_setting".
 *
 * @property integer $id
 * @property integer $user
 * @property string $kodmo
 */
class DialSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dial_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'kodmo'], 'required'],
            [['user'], 'integer'],
            [['kodmo'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'kodmo' => 'Kodmo',
        ];
    }


    /**
     * @param $user
     * @param $kodmo
     */
    public static function saveMo($user, $kodmo)
    {
        $setting = new DialSetting();
        $setting->user = $user;
        $setting->kodmo = $kodmo;

        $setting->save();
    }

    /**
     * @param $user
     * @param $kodmo
     */
    public static function removeMo($user, $kodmo)
    {
        $setting = self::findOne(['user' => $user, 'kodmo' => $kodmo]);
        if($setting)
            $setting->delete();
    }

    /**
     * @param $user
     * @param $kodmo
     * @return static
     */
    public static function checkMo($user, $kodmo)
    {
       return self::findOne(['user' => $user, 'kodmo' => $kodmo]);
    }
}
