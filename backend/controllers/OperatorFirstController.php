<?php

namespace backend\controllers;

use app\models\StmtRelate;
use backend\models\AnswerScript;
use backend\models\Cel;
use backend\models\Kladr;
use backend\models\Login;
use backend\models\MnResultStatement;
use backend\models\MnStatement;
use backend\models\MnStatementAction;
use backend\models\People;
use backend\models\Statement;
use backend\models\StatementAction;
use backend\models\Stmt;
use backend\models\StmtAction;
use backend\models\StmtCall;
use backend\models\StmtDeffered;
use backend\models\StmtTransfer;
use Yii;
use yii\base\ErrorException;
use yii\web\HttpException;
use yii\web\Response;
use backend\models\SipAccount;

class OperatorFirstController extends \yii\web\Controller
{
    public $layout = 'sip';
    public $client;
    public $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';
    public $sipEndpoints = array('rt', 'mypbx');

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Sip account
     */
    public function actionGetSipAccount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $sip = new SipAccount();
        $setting = $sip->getSipSetting();

        return $setting;
    } 

    /**
     * UID номер разговора (первичный № обращения)
     */
    public function actionGetchannel()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

//        $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';
//        $amiAddress = 'ami:123@192.168.1.153:5038';
        $logger = new \Zend\Log\Logger();
        $logWriter = new \Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        $this->client = new \phparia\Client\Phparia($logger);
        $this->client->connect($this->ariAddress);

        if(!$this->client->channels()->getChannels())
            return false;

        $channelEnd = $this->client->endPoints()
            ->getEndpointByTechAndResource('PJSIP', SipAccount::getUserNumber(Yii::$app->user->id))
            ->getChannelIds();

        if(empty($channelEnd))
        {
//            $channelEnd = $this->client->endPoints()
//                ->getEndpointByTechAndResource('PJSIP', 'mypbx')
//                ->getChannelIds();
        }

        $linkedid = Cel::find()
            ->where(['IN', 'uniqueid', $channelEnd])
            ->andWhere(['eventtype' => 'ANSWER'])
            ->asArray()->one();

        if($linkedid)
            return $linkedid['linkedid'];

        throw new HttpException(204, 'Empty value');

    }

    /**
     * Редактирование обращения
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function actionGetistask()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new Stmt();
        $isStatement = $statement->find()
            ->where(['stmt.id' => $request->id])
            ->joinWith(['group', 'theme', 'send', 'call_first', 'stage', 'stmt_status', 'deffered', 'related'])
            ->asArray()
            ->one();

        if($isStatement != null)
        {
            $isStatement['statement_date'] = Yii::$app->formatter->asDatetime($isStatement['statement_date']);
            return $isStatement;
        }

        return false;
    }

    /**
     * Просмотр обращения
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function actionGetviewtask()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new Stmt();
        $isStatement = $statement->find()
            ->where(['stmt.id' => $request->id])
            ->joinWith(['group', 'theme', 'send', 'deffered', 'call_first', 'action', 'stmt_status', 'stage'])
            ->asArray()
            ->one();

        if($isStatement != null)
        {
            return $isStatement;
        }

        return false;
    }

    /**
     * Создание нового обращения
     */
    public function actionSettask()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        /* Есть ли уже такая запись */
        $getID = Stmt::find()
            ->joinWith(['call_first'])
            ->where(['channel_id' => $request->channel_id])
            ->one();

        /* запись в БД новое обращение */
        if(!$getID)
        {
            $stmt = Stmt::createCallStmt($request);
            // Сохранение РКК и запись в табл звонков.
            if($stmt)
            {
                $stmt_id = $stmt->id;
                StmtCall::newCallStmt($stmt_id, $request);
                StmtAction::createAction($stmt_id, Yii::$app->user->id, 1, 'Создание обращения');

                /* Сохранение и переадресация */
                if(isset($request->transfer))
                {
                    Stmt::updateUser_o($stmt_id, SipAccount::getUserID($request->send_user), "1");
                //    StmtTransfer::setTransferList($stmt_id, $request);

                    StmtAction::createAction($stmt_id, Yii::$app->user->id, 2, 'Переадресация на другого специалиста');
                    StmtAction::createAction($stmt_id, SipAccount::getUserID($request->send_user), 3, 'Ответ на переадресованный звонок');
                }

                /* Сохранение информации об обратившемся */
                if(isset($request->defer))
                {
                    $this->defferdCall($stmt_id, $request->defer);
                }

                /* Сохранение связанных обращений */
                if(isset($request->relate))
                {
                    $this->relateStmt($stmt_id, $request->relate);
                }


            return $stmt_id;
            }
        }

    return false;
    }

    public function updateStmts($data, $user, $status)
    {
        $model = Statement::find()->where(['channel_id' => $data->channel_id])->one();

        $model->send_user = SipAccount::getUserID($user);
        $model->status = $status;

        if( $model->update())
        {
            $this->saveStmtAction($model, $model->user_id, 'Завершен', '3');
        }
    }

    /**
     * Сохранение обращения оператор 1-го уровня
     */
/*    public function createStmt($request, $user_id)
    {
        $model = new Statement();
        $date =  new \DateTime();

        $model->channel_id = $request->channel_id;
        $model->statement_date = $date->format('d-m-Y H:i:s');
        $model->statement = 2;
        $model->tip_statement = $request->tip_statement;
        $model->theme_statement = isset($request->theme_statement->key_statement)?$request->theme_statement->key_statement:'';
        $model->theme_statement_description = $request->theme_statement_description;

        $model->name = isset($request->name)?$request->name:'';
        $model->l_name = isset($request->l_name)?$request->l_name:'';
        $model->f_name = isset($request->f_name)?$request->f_name:'' ;
        $model->dt = isset($request->date)?$request->date:'';

        $model->user_id = $user_id;
        $model->status = '1';

        if($model->validate() && $model->save())
        {
            $this->saveStmtAction($model, $model->user_id, '1');
        }
    }*/

    /**
     * Отсроченный ответ
     * @param $id
     * @param $data
     */
    public function defferdCall($id, $data)
    {
        $defer = new StmtDeffered();
        $date =  new \DateTime($data->dt);

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

    /**
     * Сохранение отсроченного звонка
     */
    public function actionSavedefferd()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        switch ($request->saveDeffered) {
            case 0: // Закрыть обращение
                Stmt::closeStmt($request->id, "2");
                StmtAction::createAction($request->id, Yii::$app->user->id, 5, 'Оператор закрыл обращение');
                return $request;
                break;
            case 1: // Изменить статус обращения на «В работе»
                Stmt::closeStmt($request->id, "1");
                StmtDeffered::closeDeffered($request->id);
                StmtAction::createAction($request->id, Yii::$app->user->id, 1013, 'Оператор изменил статус обращения на «В работе»');
                return $request;
                break;
            default:
                return false;
        }

    }

    public function relateStmt($id, $data)
    {
        if(isset($data->id))
        {
            $items =  array_filter((array)$data->id);
            foreach ($items as $k=>$v)
            {
                $relate = new StmtRelate();

                $relate->stmt_id = $id;
                $relate->related_id = $k;
                $relate->save();
            }
        }

    }

    /**
     * Обновление/закрытие обращения
     * @param $request
     * @throws \Exception
     */
    public function updateStmt($request)
    {
        $model = new Statement();
        $data = $model->find()
            ->where(['channel_id' => $request->channel_id])
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->andWhere(['send_user' => null])
            ->one();

        $data->status = 'Завершен';

        if($data->update())
        {
            $this->saveStmtAction($data, $data->user_id, 'Завершен', '3');
        }
    }

    /**
     * @param $stmt
     * @param null $accept
     * @param null $msg
     * @param $user
     * @param $status
     */
    public function saveStmtAction($stmt, $user, $status,  $accept = null, $msg = null)
    {
        $statement_action = new StatementAction();
        $date =  new \DateTime();

        $statement_action->channel_id = $stmt->channel_id;
        $statement_action->user_id = $user;
        $statement_action->dt = Yii::$app->formatter->asDatetime($date->format('d-m-Y H:i:s'));
        $statement_action->status = $status;
        $statement_action->accept = $accept;
        $statement_action->msg = $msg;

        $statement_action->save();
    }

    /**
     * Все обращения
     */
    public function actionGetstatement()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $stmt = new Stmt();
        $items = $stmt->find()
     //       ->where(['stmt_call.in_user' => Yii::$app->user->id])
            ->where(['stmt.user_o' => Yii::$app->user->id])
            ->andWhere(['NOT IN', 'stmt.status', ["0"]])
            ->joinWith(['group', 'theme', 'send', 'call_first', 'stmt_status', 'deffered'])
            ->orderBy([
                'status' => SORT_ASC,
             // 'statement_date' => SORT_DESC,
            ])
            ->asArray()
            ->all();

	  if($items)
      	  {
            return $items;
      	  }

        return false;
    }

    /**
     * ID звонка Asterisk
     * @return array|bool|null|\yii\db\ActiveRecord
     * @throws \phparia\Exception\InvalidParameterException
     * @throws \phparia\Exception\NotFoundException
     */
    public function actionGetchnl()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

            $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';
//        $amiAddress = 'ami:123@192.168.1.153:5038';
            $logger = new \Zend\Log\Logger();
            $logWriter = new \Zend\Log\Writer\Stream("php://output");
            $logger->addWriter($logWriter);
            $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
            $logWriter->addFilter($filter);

            $this->client = new \phparia\Client\Phparia($logger);
            $this->client->connect($this->ariAddress);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $channelEnd = $this->client->endPoints()
                ->getEndpointByTechAndResource('PJSIP', SipAccount::getUserNumber(Yii::$app->user->id))
                ->getChannelIds();

            $linkedid = Cel::find()->where(['uniqueid' => $channelEnd, 'eventtype' => 'ANSWER'])->asArray()->one();

            if(isset($linkedid) && !empty($linkedid))
            {
                $stmt = new Stmt();
                $items = $stmt->find()
                    ->where(['stmt_call.channel_id' => $linkedid['linkedid'] ])
                    ->andWhere(['!=', 'stmt_call.send_user', null])
                    ->joinWith(['group', 'theme', 'send', 'call_first', 'action', 'deffered'])
                    ->orderBy([
                        'statement_date' => SORT_DESC,
                        'action_date' => SORT_ASC
                    ])
                    ->asArray()
                    ->one();

                if($items)
                {
                    return $items;
                }
                return false;
            }

        return false;
    }

    /**
     * ID текущего соединения
     */
    public function actionGetCurrentChannel()
    {
        $logger = new \Zend\Log\Logger();
        $logWriter = new \Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        $this->client = new \phparia\Client\Phparia($logger);
        $this->client->connect($this->ariAddress);

        $channelEnd = $this->client->endPoints()
            ->getEndpointByTechAndResource('PJSIP', SipAccount::getUserNumber(Yii::$app->user->id))
            ->getChannelIds();

        $linkedid = Cel::find()->where(['uniqueid' => $channelEnd, 'eventtype' => 'ANSWER'])->asArray()->one();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $linkedid['linkedid'];
    }

    /**
     * Список тем для обращений
     */
    public function actionGetliststatement($data = null)
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new MnStatement();
        $arr = $statement->find()->where(['group_statement' => $request])->all();

        return $arr;
    }

    /**
     *  Кладр
     */
    public function actionGetlistkladr()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!empty($request->address))
        {
            $model = Kladr::find()
                ->select(['OKATO as id','NAME as text', 'SOCR'])
                ->where(['LIKE', 'NAME', $request->address])
                ->andWhere('OKATO between 07000 and 07999')
                ->andWhere(['IN', 'LEVELID', [3,4]])
                ->limit(20)
                ->asArray()
                ->all();

            return $res['results'] = $model;
        }else{
            $model = Kladr::find()
                ->select(['OKATO as id','NAME as text', 'SOCR'])
                ->where('OKATO between 07000 and 07999')
                ->andWhere(['IN', 'LEVELID', [3]])
                ->limit(20)
                ->asArray()
                ->all();

            return $res['results'] = $model;
        }
        return false;
    }

    /**
     * Список адресов в телефонной книги
     */
    public function actionGetlistphonebook()
    {
        $user = new SipAccount();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $arr = array();
        $resData = array();

        $list = $user->find()->with(['user'])->asArray()->all();

        $logger = new \Zend\Log\Logger();
        $logWriter = new \Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        $this->client = new \phparia\Client\Phparia($logger);
        $this->client->connect($this->ariAddress);

        if($list)
        {
            foreach ($list as $item)
            {
                (array)$item['status'] = $this->client->endPoints()->getEndpointByTechAndResource('PJSIP', $item['sip_private_identity'])->getState();
                (array)$item['channels'] = $this->client->endPoints()->getEndpointByTechAndResource('PJSIP', $item['sip_private_identity'])->getChannelIds();
                $arr[]= $item;
            }

            foreach ($arr as $row)
            {
                $resData[$row['user']['company']['name']][] = $row;
            }

            return $resData;
        }

        return false;
    }

    /**
     * Закрытие обращения
     */
    public function actionSetupdatetask()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new Stmt();
        $data = $statement->findOne($request->id);

        $data->status = '2';

        $msg = isset($request->accept_msg)?$request->accept_msg:"";
        if($data->update())
        {
            StmtAction::createAction($request->id, Yii::$app->user->id, $request->data->accept, $msg);
        }
    }

    /**
     * Поиск по реестру застрахованных
     */
    public function actionGetfinderz()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        if(isset($request->dateMan))
        {
            $date =  new \DateTime($request->dateMan);
            $date->add(new \DateInterval('P1D'));
            $request->dateMan = $date->format('m.d.Y');
        }

        $model = new People();
        $attributes = [
            'Name' => isset($request->Name)?$request->Name:'',
            'pName' => isset($request->pName)?$request->pName:'',
            'sName' => isset($request->sName)?$request->sName:'',
            'dateMan' => isset($request->dateMan)?$request->dateMan:'',
        ];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = $model->find()
            ->where(array_filter($attributes))
            ->with(['reestr', 'stik'])
            ->limit(10)
            ->asArray()
            ->all();

        return $data;
    }

    /**
     *  История обращения
     */
    public function actionGethistory()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $attributes = [
            'id_erz' => isset($request->ENP)?$request->ENP:false,
        ];

        $stmt = new Stmt();
        $items = $stmt->find()
            ->where(['stmt_deffered.id_erz' => $request->ENP])
            ->joinWith(['group', 'theme', 'send', 'stmt_status', 'deffered', 'user'])
            ->orderBy([
                'status' => SORT_ASC,
                // 'statement_date' => SORT_DESC,
            ])
            ->asArray()
            ->all();

        return empty($items)?null:$items;
    }

    /**
     * Результат обращения
     */
    public function actionGetstmtaction(){

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $stmt = new MnStatementAction();
        $res = $stmt->find()
            ->where(['type' => 2])
            ->asArray()
            ->all();

        return $res;
    }

    /**
     * Список сценариев ответа по выбранной теме
     */
    public function actionGetlistanswerscript()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = new AnswerScript();
        $answers = $model->find()
            ->where(['key_statement' => $request->key_statement])
            ->asArray()->all();

        $res = array();
        foreach ($answers as $item)
        {
            $getUser = array();
            $vowels = array('"', '[', ']');
            $users = str_replace($vowels, "", $item['recomend_users']);
            $users = explode(",", $users);

            foreach ($users as $user)
            {
                $fio = SipAccount::find()
                    ->where(['sip_private_identity' => $user])
                    ->with(['username'])
                    ->asArray()
                    ->one();
                $getUser[] = $fio;
            }

            $item['login'] = $getUser;
            $res['current'][] = $item;
        }

        $all = $model->find()->asArray()->all();

        $res['all'] = $all;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $res;
    }

    /**
     * @param $sip
     * @return mixed
     */
    protected function parseSip($sip)
    {
        $str = str_replace(array('>', '<'), "", $sip);
        $str = explode('@', $str);

        $str = explode(':', $str[0]);

        return $str[1];
    }
    
    public function actionGetendpointschannel(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);


        $logger = new \Zend\Log\Logger();
        $logWriter = new \Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        $this->client = new \phparia\Client\Phparia($logger);
        $this->client->connect($this->ariAddress);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $channelEnd = $this->client->endPoints()
            ->getEndpointByTechAndResource('PJSIP', SipAccount::getUserNumber(Yii::$app->user->id))
            ->getChannelIds();

        $linkedid = Cel::find()->where(['uniqueid' => $channelEnd, 'eventtype' => 'ANSWER'])->asArray()->one();
        
        return StmtTransfer::endpointsTransfer($linkedid['linkedid']);

    }

    /**
     * Обновление ответственного оператора
     * @return bool
     * @throws \Exception
     */
    public function actionSetdelayed()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = Stmt::findOne($request->id);

        $model->user_o = SipAccount::getUserID($request->transfer->sip_private_identity);
       // $model->status = "5";

        if($model->update())
            return true;

        return false;
    }

    public function actionSetdelayedtransfer()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = Stmt::findOne($request->id);

        $model->user_o = SipAccount::getUserID($request->transfer->sip_private_identity);
        $model->status = "5";

        if($model->update())
        {
            $channel = $this->actionGetCurrentChannel();
            StmtCall::transferCallStmt($request, $channel[0] );
            return $request;
        }

        return false;
    }

    /**
     * Список всех операторов
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGettransferusers()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user = new SipAccount();

        $users = $user->find()
            ->where(['IN', 'role_type', [1, 2]])
            ->joinWith(['user'])
            ->asArray()
            ->all();

        return $users;
    }


    /**
     * Редактирование обращения
     */
    public function actionCompletestmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        /* Есть ли уже такая запись */
        $getID = Stmt::findOne($request->id);

        /* запись в БД новое обращение */
        if($getID)
        {
            $stmt = Stmt::updateCallStmt($request);
//            // Сохранение РКК и запись в табл звонков.
            if($stmt)
            {
                $stmt_id = $stmt->id;
                /* Сохранение и переадресация */
                if(isset($request->transfer))
                {
                    Stmt::updateUser_o($stmt_id, SipAccount::getUserID($request->send_user), "1");
                    StmtAction::createAction($stmt_id, Yii::$app->user->id, 2, 'Переадресация на другого специалиста');
                    StmtAction::createAction($stmt_id, SipAccount::getUserID($request->send_user), 3, 'Ответ на переадресованный звонок');
                }

                /* Сохранение информации об обратившемся */
                if(isset($request->deffered))
                {
                    StmtDeffered::updateCall($stmt_id, $request->deffered);
                }

                /* Сохранение связанных обращений */
                if(isset($request->relate))
                {
                    $this->relateStmt($stmt_id, $request->relate);
                }

                /* Закрытие обращения */
                if(isset($request->data))
                {
                    if(!empty($request->data->accept))
                    {
                        // $this->relateStmt($stmt_id, $request->relate);
                        Stmt::closeStmt($stmt_id, '2');
                        StmtAction::createAction($stmt_id,  Yii::$app->user->id, $request->data->accept, $request->data->accept_msg);
                    }
                }

                return $request;
            }
        }
        return false;
    }


//    public function actionSipaccount()
//    {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        // Sip Config
//        $config = array(
//            'sipDisplayName' => '1003',
//            'sipPrivateIdentity' => '1003',
//            'sipPublicIdentity' => "sip:1003@192.168.1.153",
//            'sipPassword' => "1234qwer",
//            'sipRealm' => "192.168.1.153",
//            'sip_websocket_proxy_url' => "ws://192.168.1.153:8088/ws",
//            'sip_outbound_proxy_url' => "192.168.1.153:5061",
//            'sip_ice_servers' => "[]",
//            'sip_disable_video' => true
//        );
//        return $config;
//    }
}
