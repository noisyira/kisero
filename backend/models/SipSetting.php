<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "SipSetting".
 *
 * @property integer $id
 * @property string $sip_websocket_proxy_url
 * @property string $sip_outbound_proxy_url
 * @property string $sip_ice_servers
 * @property integer $sip_disable_video
 */
class SipSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SipSetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sip_websocket_proxy_url', 'sip_outbound_proxy_url', 'sip_ice_servers'], 'string'],
            [['sip_disable_video'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sip_websocket_proxy_url' => 'Sip Websocket Proxy Url',
            'sip_outbound_proxy_url' => 'Sip Outbound Proxy Url',
            'sip_ice_servers' => 'Sip Ice Servers',
            'sip_disable_video' => 'Sip Disable Video',
        ];
    }
}
