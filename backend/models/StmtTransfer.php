<?php

namespace backend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stmt_transfer".
 *
 * @property integer $id
 * @property integer $stmt_id
 * @property string $dt
 * @property integer $u_from
 * @property integer $u_to
 */
class StmtTransfer extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stmt_transfer';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt']
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stmt_id', 'u_from', 'u_to'], 'required'],
            [['stmt_id', 'u_from', 'u_to'], 'integer'],
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
            'dt' => 'Dt',
            'u_from' => 'U From',
            'u_to' => 'U To',
        ];
    }

    /**
     * История звонков и переадресаций
     */
    public static function setTransferList($id, $req)
    {
        $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';

        $logger = new \Zend\Log\Logger();
        $logWriter = new \Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        $client = new \phparia\Client\Phparia($logger);
        $client->connect($ariAddress);

        $endpoint = $client->endPoints()
            ->getEndpointByTechAndResource('PJSIP', SipAccount::getUserNumber(Yii::$app->user->id))
            ->getChannelIds();

        $num = $client->channels()->getChannel($endpoint[0])->getCaller()->getNumber();

        $endpoint = $client->endPoints()
            ->getEndpointByTechAndResource('PJSIP', $num)
            ->getChannelIds();

        StmtTransfer::saveTransfer($id, $req);

        foreach ($endpoint as $item)
        {
                $model = new StmtTransfer();

                if(!$model->find()->where(['channel_id' => $item])->one())
                {
                    $model->stmt_id = $id;
                    $model->sip = SipAccount::getUserNumber(Yii::$app->user->id);
                    $model->channel_id = $item;

                    $model->save();
                }
        }

//        $num = $client->channels()->getChannel($endpoint[0])->getConnected()->getNumber();
//
//        $endpoint = $client->endPoints()
//            ->getEndpointByTechAndResource('PJSIP', $num)
//            ->getChannelIds();
//
//        StmtTransfer::saveTransfer($id, $endpoint);
//
//        if($endpoints)
//        {
//            foreach ($endpoint as $item)
//            {
//                $model = new self();
//
//                $model->stmt_id = $id;
//                $model->sip = SipAccount::getUserNumber(Yii::$app->user->id);
//                $model->channel_id = $item;
//
//                $model->save();
//            }
//        }
    }


    public static function saveTransfer($id, $req)
    {
        $model = new StmtTransfer();

        $model->stmt_id = $id;
        $model->sip = SipAccount::getUserNumber(Yii::$app->user->id);
        $model->channel_id = $req->channel_id;

        $model->save();
    }

    public static function endpointsTransfer($endpoints)
    {
        $query = StmtCall::find()->where(['channel_id' => $endpoints])->all();

        return $endpoints;
    }
}
