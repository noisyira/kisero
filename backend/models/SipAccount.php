<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sipAccount".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $sip_dispaly_name
 * @property string $sip_private_identity
 * @property string $sip_public_identity
 * @property string $sip_password
 * @property string $sip_realm
 * @property string $sip_type
 * @property string $sip_outer
 */
class SipAccount extends \yii\db\ActiveRecord
{
    public $createSIP = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sipAccount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
 //           [['user_id', 'sip_dispaly_name', 'sip_private_identity', 'sip_password'], 'required'],
            [['user_id'], 'integer'],
            [['sip_dispaly_name', 'sip_private_identity', 'sip_public_identity', 'sip_password', 'sip_realm', 'sip_type', 'sip_outer'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'sip_dispaly_name' => 'Имя пользователя',
            'sip_private_identity' => 'Внутренний номер',
            'sip_public_identity' => 'Строка подключения',
            'sip_password' => 'Пароль аккаунта',
            'sip_realm' => 'Адрес подключения',
            'sip_type' => 'Тип аккаунта',
            'sip_outer' => 'Работа через VipNet',
            'createSIP' => 'Уставновить настройки SIP',
        ];
    }

    public function create($id)
    {
        $sip = new SipAccount();

        $sip->user_id = $id->id;
        $sip->sip_dispaly_name = $this->sip_dispaly_name;
        $sip->sip_private_identity = $this->sip_private_identity;
        $sip->sip_public_identity = $this->sip_public_identity;
        $sip->sip_password = $this->sip_password;
        $sip->sip_realm = $this->sip_realm;
        $sip->sip_type = $this->sip_type;
        $sip->sip_outer = $this->sip_outer;

        if(!empty($this->sip_private_identity))
        {
            $this->sendAuth($this->sip_private_identity, $this->sip_password);

            return $sip->save() ? $sip : null;
        }
        return false;
    }

    /**
     * Настройки SIP для пользователя
    */
    public function getSipSetting()
    {
        $userID = Yii::$app->user->id;
        $s_setting = SipSetting::find()->one();
        $sip = self::find()->where(['user_id' => $userID])->one();

        $setting = array_merge($s_setting->toArray(), $sip->toArray());
        return $setting;
    }

    /**
     * Sip setting for userID
     */
    public static function getNumber($id)
    {
        $num = self::find()->where(['user_id' => $id])->one();

        return $num;
    }

    public static function recomendUsers()
    {
        $users = self::find()->with('user')->all();

        $result = ArrayHelper::map($users, 'sip_dispaly_name', function($data, $default){
            $info = $data->sip_dispaly_name .'<br>'.
                '<small>'.
                $data->user->fam .' '. $data->user->im .' '. $data->user->ot
                .'<br> '.$data->user->company.' </small>';
            return $info;
        });

        return $result;
    }

    /**
     * Внутренний номер оператора
     */
    public static function getUserNumber($id)
    {
        $num = self::find()->where(['user_id' => $id])->one();

        if($num)
        {
            return $num->sip_private_identity;
        }
        return '';
    }

    /**
     * @param $sip
     * @return mixed|string
     */
    public static function getUserID($sip)
    {
        $user = self::find()->where(['sip_private_identity' => $sip])->one();

        if($user){return $user->user_id;}

        return '';
    }

    /**
     * ФИО оператора
     */
    public static function getFIO($user_id)
    {
        $user = Login::find()->select('fam, im, ot')->where(['id' => $user_id])->one();

        if($user)
            return $user->fam.' '.$user->im.' '.$user->ot;
        return 'не указанно';
    }

    public function getUser()
    {
        return $this->hasOne(Login::className(), ['id' => 'user_id'])->with(['company']);
    }

    /**
     * ФИО оператора и его внутренний номер
     * @param $channel
     * @return string
     */
    public static function answerUser($channel)
    {
        if($channel)
        {
            $num = explode('/', stristr($channel, '-', true));

            if($num[1])
            {
                $fio = self::getFIO(self::getUserID($num[1]));

                if($fio == 'не указанно')
                    return false;

                return $fio;
            }
        }
        return false;
    }

    /**
     * Роль пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getUsername()
    {
        return $this->hasOne(Login::className(), ['id' => 'user_id']);
    }

    /* Отправка Auth */
    public function sendAuth($id, $pass = null)
    {

        /* Отправка Auth */
        $data = array(
            array('attribute' => 'auth_type', 'value' => 'userpass'),
            array('attribute' => 'username', 'value' => "$id"),
            array('attribute' => 'password', 'value' => "$pass"),
        );

        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/auth/" . $id . "?api_key=Denis:KYlWXL1i4-bZmrr";

        $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode(array("fields" => $data));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result)
            $this->sendAor($id);
    }

    /* Отправка Aor */
    public function sendAor($id)
    {
        $data = array(
            array('attribute' => 'support_path', 'value' => 'yes'),
            array('attribute' => 'remove_existing', 'value' => 'yes'),
            array('attribute' => 'max_contacts', 'value' => '1'),
            //         array('attribute' => 'contact', 'value' => 'sip:'.$id.'@192.168.5.62:5062'),
        );

        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/aor/" . $id . "?api_key=Denis:KYlWXL1i4-bZmrr";

        $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode(array("fields" => $data));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result)
            $this->sendEndpoint($id);
    }

    /* Отправка Endpoint */
    public function sendEndpoint($id)
    {
        $data = array(
            //      array('attribute' => 'from_user', 'value' => "$id"),
            array('attribute' => 'use_avpf', 'value' => "yes"),
            array('attribute' => 'rtp_symmetric', 'value' => "yes"),
            array('attribute' => 'allow', 'value' => 'ulaw'),
            array('attribute' => 'transport', 'value' => 'transport-ws'),
            array('attribute' => 'media_encryption', 'value' => 'dtls'),
            array('attribute' => 'direct_media', 'value' => 'no'),
            array('attribute' => 'dtls_verify', 'value' => 'no'),
            array('attribute' => 'callerid', 'value' => '' . $id . ' <' . $id . '>'),
            array('attribute' => 'dtls_cert_file', 'value' => '/etc/asterisk/keys/asterisk.pem'),
            array('attribute' => 'dtls_ca_file', 'value' => '/etc/asterisk/keys/ca.crt'),
            array('attribute' => 'dtls_setup', 'value' => 'actpass'),
            array('attribute' => 'media_use_received_transport', 'value' => 'yes'),
            array('attribute' => 'ice_support', 'value' => 'yes'),
            array('attribute' => 'rewrite_contact', 'value' => 'no'),
            array('attribute' => 'context', 'value' => 'from-internal'),
            array('attribute' => 'auth', 'value' => "$id"),
            array('attribute' => 'aors', 'value' => "$id"),
        );

        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/endpoint/" . $id . "?api_key=Denis:KYlWXL1i4-bZmrr";

        $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode(array("fields" => $data));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public static function deleteEndpoint($id, $name)
    {
        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/".$name."/".$id."?api_key=Denis:KYlWXL1i4-bZmrr";

        $ch = curl_init( $url );
        # Setup request to send json via POST.

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);

    }

}
