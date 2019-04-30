<?php

namespace backend\controllers;

use backend\components\Helpers;
use backend\models\Cel;
use backend\models\Kladr;
use backend\models\Login;
use backend\models\MnResultStatement;
use backend\models\MnSendStatement;
use backend\models\MnStatement;
use backend\models\MnStatementAction;
use backend\models\People;
use backend\models\Statement;
use backend\models\StatementAction;
use backend\models\Stmt;
use backend\models\StmtAction;
use backend\models\StmtAttachment;
use backend\models\StmtCall;
use backend\models\StmtDeffered;
use Yii;
use yii\debug\models\search\Log;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use backend\models\SipAccount;

class OperatorSecondController extends \yii\web\Controller
{
    public $layout = 'sip-2';
    public $client;
    public $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';


    public function beforeAction($action) {
        $this->enableCsrfValidation = ($action->id !== "getupload");
        return parent::beforeAction($action);
    }


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

        foreach($setting as $key=>$value)
        {
            $setting[$key] = trim($value);
        }

        return $setting;
    }

    /**
     * UID номер разговора (первичный № обращения)
     * @return mixed
     */
    public function actionGetchannel()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sip = SipAccount::getNumber(Yii::$app->user->id);
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

        if(empty($channelEnd))
        {
            $channelEnd = $this->client->endPoints()
                ->getEndpointByTechAndResource('PJSIP', 'mypbx')
                ->getChannelIds();
        }

        $linkedid = Cel::find()->where(['uniqueid' => $channelEnd, 'eventtype' => 'ANSWER'])->asArray()->one();

        return $linkedid['linkedid'];
    }

    /**
     * UID номер разговора (первичный № обращения)
     * @return mixed
     */
    public function actionGetchnl()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';
//        $amiAddress = 'ami:123@192.168.1.153:5038';
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

        if(empty($channelEnd))
        {
            $channelEnd = $this->client->endPoints()
                ->getEndpointByTechAndResource('PJSIP', 'mypbx')
                ->getChannelIds();
        }

        $linkedid = Cel::find()
            ->where(['IN', 'uniqueid', $channelEnd])
            ->andWhere(['eventtype' => 'ANSWER'])
            ->asArray()->one();

        if(isset($linkedid) && !empty($linkedid))
        {
            $id = $linkedid['linkedid'];
            $stmt = new Stmt();
            $items = $stmt->find()
                ->where(['stmt_call.channel_id' => $id])
                ->joinWith(['group', 'theme', 'send', 'call_second', 'deffered'])
                ->orderBy([
                    'statement_date' => SORT_DESC,
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
     * UID номер разговора (первичный № обращения)
     * @return mixed
     */
    public function actionAcceptcall()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        if(!empty($request->sip))
        {
            $sip = $this->parseSip($request->sip);

            $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:123&app=dialer';
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

            if(isset($linkedid) && !empty($linkedid))
            {
                $stmt = new Stmt();
                $items = $stmt->find()
                    ->where(['stmt_call.channel_id' => $linkedid['linkedid']])
                    ->one();

                $items->user_o = Yii::$app->user->id;

                if($items->update())
                {
                    return $items;
                }
                return false;
            }
        }
        return false;
    }

    /**
     * Добавление нового обращения
     */
    public function actionSettask()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = new Statement();
        $sip = new SipAccount();
        $date =  new \DateTime();

        if(!$model->findOne($request->id))
        {
            $model->id = $request->id;
            $model->statement_date = $date->format('d-m-Y H:i:s');
            $model->statement = $request->statement;
            $model->tip_statement = $request->tip_statement;
            $model->theme_statement = $request->theme_statement;
            $model->theme_statement_description = $request->theme_statement_description;

            $model->name = $request->name;
            $model->l_name = $request->l_name;
            $model->f_name = $request->f_name;
            $model->dt = $request->date;

            $model->sip = $sip->getUserNumber(Yii::$app->user->id);
            $model->status = 'В работе';

            if($model->validate() && $model->save())
            {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Все обращения
     */
    public function actionGetstatement()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $stmt = new Stmt();

        if(Login::getTypeUser(Yii::$app->user->id) == 2)
        {
            /* Все обращения по организации */
            $items = $stmt->find()
                ->where(['stmt.company' => Login::companyID(Yii::$app->user->id)])
                ->andWhere(['NOT IN', 'stmt.status', ["0"]])
                //  ->orWhere(['stmt.user_o' => Yii::$app->user->id])
                ->joinWith(['group', 'theme', 'send', 'call_second', 'stmt_status', 'deffered'])
                ->orderBy([
                    'status' => SORT_ASC,
                ])
                ->asArray()
                ->all();
        } else {
            /* Все обращения оператора */
            $items = $stmt->find()
                //    ->where(['stmt_call.send_user' => Yii::$app->user->id])
                ->where(['stmt.user_o' => Yii::$app->user->id])
                ->andWhere(['NOT IN', 'stmt.status', ["0"]])
                ->joinWith(['group', 'theme', 'send', 'call_second', 'stmt_status', 'deffered'])
                ->orderBy([
                    'status' => SORT_ASC,
                ])
                ->asArray()
                ->all();
        }

        if($items)
        {
            return $items;
        }
        return false;
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
            ->where(['stmt.id' => $request->id, 'stmt.company' => Login::companyID(Yii::$app->user->id)])
            ->andWhere(['NOT IN', 'stmt.status', ["0"]])
            ->joinWith(['group', 'theme', 'send', 'user', 'call_second', 'stage', 'stmt_status', 'deffered',
                'attachment', 'related'])
            ->asArray()
            ->one();

        if($isStatement != null)
        {
            return Helpers::formatDate($isStatement);
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
        $account = Login::getTypeUser(Yii::$app->user->id);

        if(Login::getTypeUser(Yii::$app->user->id) == 2)
        {
            $isStatement = $statement->find()
                ->where(['stmt.id' => $request->id])
                ->andWhere(['NOT IN', 'stmt.status', ["0"]])
                ->joinWith(['group', 'theme', 'send', 'user', 'deffered', 'call_second', 'allactions', 'stmt_status',
                    'company', 'attachment', 'stage'])
                ->asArray()
                ->one();
        }else{
            $isStatement = $statement->find()
                ->where(['stmt.id' => $request->id])
                ->andWhere(['NOT IN', 'stmt.status', ["0"]])
                ->joinWith(['group', 'theme', 'send', 'deffered', 'call_second', 'allactions', 'stmt_status',
                    'user', 'company', 'attachment', 'stage'])
                ->asArray()
                ->one();
        }

        if($isStatement != null)
        {
            $isStatement['role_type'] = $account;
            return Helpers::formatDate($isStatement);
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

        $msg = isset($request->accept_msg)?$request->accept_msg:"Принятые меры";
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
            ->joinWith(['group', 'theme', 'send', 'stmt_status', 'deffered'])
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
     * Список тем для обращений
     */
    public function actionGetliststatement()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new MnStatement();
        $arr = $statement->find()->where(['group_statement' => $request])->all();

        return $arr;
    }

    /**
     * Список тем для обращений
     */
    public function actionGetliststmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new MnStatement();
        $arr = $statement->find()->with(['group'])->asArray()->all();

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
                if($this->client->endPoints()->getEndpointByTechAndResource('PJSIP', $item['sip_private_identity'])->getState())
                {
                    (array)$item['status'] = $this->client->endPoints()->getEndpointByTechAndResource('PJSIP', $item['sip_private_identity'])->getState();
                    (array)$item['channels'] = $this->client->endPoints()->getEndpointByTechAndResource('PJSIP', $item['sip_private_identity'])->getChannelIds();
                    $arr[]= $item;
                }
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
     * @param $stmt
     * @param null $accept
     * @param null $msg
     * @param $user
     */
    public function saveStmtAction($stmt, $user, $accept = null, $msg = null)
    {
        $statement_action = new StatementAction();
        $date =  new \DateTime();

        $statement_action->channel_id = $stmt->channel_id;
        $statement_action->user_id = $user;
        $statement_action->dt = $date->format('d-m-Y H:i:s');
        $statement_action->status = $stmt->status;
        $statement_action->accept = $accept;
        $statement_action->msg = $msg;

        $statement_action->save();
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

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $channelEnd = $this->client->endPoints()
            ->getEndpointByTechAndResource('PJSIP', SipAccount::getUserNumber(Yii::$app->user->id))
            ->getChannelIds();

        $linkedid = Cel::find()->where(['uniqueid' => $channelEnd, 'eventtype' => 'ANSWER'])->asArray()->one();

        return $linkedid['linkedid'];
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

        $model->user_o = SipAccount::getUserID($request->transfer->sip_private_identity)->user_id;
        $model->company = Login::getCompanyUser($request->transfer->user_id)->company;
        $model->status = "1";

        if($model->update())
            return true;

        return '';
    }

    public function actionSetdelayedtransfer()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = Stmt::findOne($request->id);

        $model->user_o = SipAccount::getUserID($request->transfer->sip_private_identity);
        $model->company = Login::getCompanyUser(SipAccount::getUserID($request->transfer->sip_private_identity));
        $model->status = "1";

        if($model->update())
        {
            $channel = $this->actionGetCurrentChannel();
            StmtCall::transferCallStmt($request, $channel[0] );
            return $request;
        }

        return false;
    }

    /**
     * Список всех операторов для переадресации
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGettransferusers()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user = new SipAccount();

        $users = $user->find()
            ->where(['role_type' => 1])
            ->joinWith(['user'])
            ->asArray()
            ->all();

        return $users;
    }


    public function actionFilteruserlist()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userRole = Login::getTypeUser(Yii::$app->user->id);
        $user = new SipAccount();

        switch ($userRole) {
            case 1:
                $users = Login::find()
                    ->where(['id' => Yii::$app->user->id])
                    ->asArray()
                    ->all();
                return $users;
                break;
            case 2:
                $users = Login::find()
                    ->where(['company' => Login::companyID(Yii::$app->user->id)])
                    ->asArray()
                    ->all();

                return $users;
                break;
            default:
                $users = $user->find()
                    ->where(['login.id' => Yii::$app->user->id])
                    ->joinWith(['user'])
                    ->asArray()
                    ->all();
                return $users;
        }

    }

    /**
     * Список всех видов обращений
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetmodestmt()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $mode = new MnSendStatement();

        $modes = $mode->find()
            ->asArray()
            ->all();

        return $modes;
    }

    /**
     * Новое обращение {offline}
     * @return array
     */
    public function actionSavestmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $stmt = new Stmt();
        $def = new StmtDeffered();

        $stmtData = $stmt->createStmtSecond($request);

        if($stmtData)
        {
            // Действие создание обращение;
            StmtAction::createAction($stmtData->id, Yii::$app->user->id, 1, 'Создание обращения');
        }

        if($request->defer)
        {
            // Информация об обратившемся;
            $def->createDefferedStmt($stmtData->id, $request->defer);
        }

        $result = array(
            'id' => $stmtData->id,
            'num' => isset($request->attach->num)?$request->attach->num:null,
            'date' => isset($request->attach->date)?$request->attach->date:null,
        );

        // Вернуть массив FileUpload [id, number, date]
        return $result;
    }

    /**
     * Прикрепление файлов к обращению
     */
    public function actionGetupload()
    {
        $post = $_POST;
        $file = $_FILES['file'];
        $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
        $path = Yii::getAlias('@backend/uploads/') . $post['id'];

        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        if (StmtAttachment::createAttch($post['id'], $file['name'], $file['type'], $path, $post))
            move_uploaded_file( $tempPath, $path.DIRECTORY_SEPARATOR.$file['name']);

        return '';
    }


    public function actionUpdatestmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $stmt = Stmt::updateCallStmt($request);

        if($stmt)
        {
            $stmt_id = $stmt->id;
            StmtDeffered::updateCall($stmt_id, $request->deffered);

            /* Закрытие обращения */
            if(isset($request->data))
            {
                if(!empty($request->data->accept))
                {
                    switch ($request->data->accept) {
                        case '1014':
                            Stmt::closeStmt($stmt_id, '6');
                            StmtAction::createAction($stmt_id,  Yii::$app->user->id, $request->data->accept, "Обращение на удаление");
                            break;
                        case '8':
                            Stmt::closeStmt($stmt_id, '1');
                            StmtAction::createAction($stmt_id, Yii::$app->user->id, $request->data->accept, 'Переадресация на другого специалиста');
                            break;
                        case '12':
                            Stmt::closeStmt($stmt_id, '1');
                            StmtAction::createAction($stmt_id,  Yii::$app->user->id, $request->data->accept, "Продление обращения");
                            break;

                        default:
                            $status = ($request->tip_statement == 2)? '3':'2';
                            // $this->relateStmt($stmt_id, $request->relate);
                            Stmt::closeStmt($stmt_id, $status);
                            StmtAction::createAction($stmt_id,  Yii::$app->user->id, $request->data->accept, $request->data->accept_msg);
                    }
                }
            }
        }

        return $request->data;
    }

    /**
     * Вернуть обращение исполнителю для доработки
     */
    public function actionReturnstmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        Stmt::closeStmt($request->id, "1");
        StmtAction::createAction($request->id, Yii::$app->user->id, 1013, 'Оператор изменил статус обращения на «В работе»');
    }

    /**
     * удалить обращение
     */
    public function actionRemovestmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        Stmt::closeStmt($request->id, "0");
    }

    /**
     * Первичный отчёт
     */
    public function actionGetreport()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $start = new \DateTime($request->startDate);
        $end = new \DateTime($request->endDate);

        $userRole = Login::getTypeUser(Yii::$app->user->id);
        $user = new SipAccount();

        switch ($userRole) {
            case 1:
                $model = Stmt::find()
                    ->where(['between', 'statement_date', Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end)])
                    ->andWhere(['user_o' => Yii::$app->user->id])
                    ->joinWith(['user'])
                    ->asArray()
                    ->all();
                return $model;
                break;
            case 2:
                $model = Stmt::reportTotals(Login::companyID(Yii::$app->user->id), Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end));
                $calls = Stmt::reportTotalCalls(Login::companyID(Yii::$app->user->id), Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end));
                $send = Stmt::reportTotalSend(Login::companyID(Yii::$app->user->id), Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end));
                $plaints = Stmt::reportPlaints(Login::companyID(Yii::$app->user->id), Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end));

                $model = ArrayHelper::index($model, 'k');
                $model['1.1'] = array_shift($calls);
                $model['1.2'] = array_shift($send);
                $model['2'] = array_shift($plaints);

                return $model;
                break;
            default:
                $model = Stmt::find()
                    ->where(['between', 'statement_date', Yii::$app->formatter->asDate($start), Yii::$app->formatter->asDate($end)])
                    ->andWhere(['user_o' => Yii::$app->user->id])
                    ->joinWith(['user'])
                    ->asArray()
                    ->all();

                return $model;
        }
    }

    public function actionSendreport()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Выравнивание про центру
        $al_center = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        // Выранивание по правому краю
        $al_right = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        );
        // выравнивание по середине и левому краю
        $al_center = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'velrtical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        );

        $allBorder = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $outlineBorder = array(
            'borders' => array(
                'outline' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel = new \PHPExcel();

        $sheet=0;

        $objPHPExcel->setActiveSheetIndex($sheet);

        //Ориентация страницы и  размер листа
        $objPHPExcel->getActiveSheet()->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objPHPExcel->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        //Поля документа
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setTop(0.5);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setLeft(0.75);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setBottom(0.5);

        $objPHPExcel->getActiveSheet()->setTitle('Таблица 1.1');
        $objPHPExcel->getActiveSheet()->getStyle("A1:I40")->getFont()->setSize(9);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1')->getStyle('A1:I1')->applyFromArray($al_center);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ОБРАЩЕНИЯ ЗАСТРАХОВАННЫХ ЛИЦ')
            ->getStyle()->getFont()->setSize(12);

        $objPHPExcel->getActiveSheet()->mergeCells('A2:I2')->getStyle('A2:I2')->applyFromArray($al_right);
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Таблица 1.1');

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A5');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B5')->getStyle('B3:B5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->mergeCells('C3:I3');
        $objPHPExcel->getActiveSheet()->mergeCells('C4:E4');
        $objPHPExcel->getActiveSheet()->mergeCells('F4:H4');
        $objPHPExcel->getActiveSheet()->mergeCells('I4:I5');

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Виды обращений');
        $objPHPExcel->getActiveSheet()->setCellValue('B3', '№ стр.');
        $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Количество поступивших обращений за отчетный период');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'ТФОМС');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'СМО');
        $objPHPExcel->getActiveSheet()->setCellValue('I4', 'ИТОГО');
        $objPHPExcel->getActiveSheet()->setCellValue('C5', 'устных');
        $objPHPExcel->getActiveSheet()->setCellValue('D5', 'письменных');
        $objPHPExcel->getActiveSheet()->setCellValue('E5', 'всего');
        $objPHPExcel->getActiveSheet()->setCellValue('F5', 'устных');
        $objPHPExcel->getActiveSheet()->setCellValue('G5', 'письменных');
        $objPHPExcel->getActiveSheet()->setCellValue('H5', 'всего');

        $objPHPExcel->getActiveSheet()->setCellValue('A6', '1');
        $objPHPExcel->getActiveSheet()->setCellValue('B6', '2');
        $objPHPExcel->getActiveSheet()->setCellValue('C6', '3');
        $objPHPExcel->getActiveSheet()->setCellValue('D6', '4');
        $objPHPExcel->getActiveSheet()->setCellValue('E6', '5');
        $objPHPExcel->getActiveSheet()->setCellValue('F6', '6');
        $objPHPExcel->getActiveSheet()->setCellValue('G6', '7');
        $objPHPExcel->getActiveSheet()->setCellValue('H6', '8');
        $objPHPExcel->getActiveSheet()->setCellValue('I6', '9');

        $objPHPExcel->getActiveSheet()->getStyle('A3:I6')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // Рамки
        $objPHPExcel->getActiveSheet()->getStyle('A3:I40')->applyFromArray($allBorder);
        $objPHPExcel->getActiveSheet()->getStyle('A6:I6')->applyFromArray($outlineBorder);
        $objPHPExcel->getActiveSheet()->getStyle('B3:B40')->applyFromArray($outlineBorder);

        $row = 7;
        $c = 2; // СМО

        $list = $this->listStmt();

        foreach ($list as $k=>$v)
        {
            $ident = substr_count($k, '.');
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $v)->getStyle('A'.$row)->getAlignment()->setIndent($ident + 1);;
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$row, $k, \PHPExcel_Cell_DataType::TYPE_STRING);
            /* ТФОМС */
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $this->currentValue($request[$k], 't-voice', $c, 'C'.$row));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $this->currentValue($request[$k], 't-write', $c, 'D'.$row));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, '=SUM('.'C'.$row.':'.'D'.$row.')');
            /* СМО */
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $this->currentValue($request[$k], 'smo-voice', $c, 'F'.$row));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $this->currentValue($request[$k], 'smo-write', $c, 'G'.$row));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '=SUM('.'F'.$row.':'.'G'.$row.')');
            /* ИТОГО */
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, '=SUM('.'E'.$row.'+'.'H'.$row.')');

            $row++;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('F23', '= SUM(F24:F39)' );
        $objPHPExcel->getActiveSheet()->setCellValue('G23', '= SUM(G24:G39)' );


        $objPHPExcel->getActiveSheet()->setCellValue('C7', '=SUM(C8:C9)');
        $objPHPExcel->getActiveSheet()->setCellValue('D7', '=SUM(D8:D9)');
        $objPHPExcel->getActiveSheet()->setCellValue('E7', '=SUM(E8:E9)');

        $objPHPExcel->getActiveSheet()->setCellValue('F7', '=SUM(F8:F9)');
        $objPHPExcel->getActiveSheet()->setCellValue('G7', '=SUM(G8:G9)');
        $objPHPExcel->getActiveSheet()->setCellValue('H7', '=SUM(H8:H9)');

        $objPHPExcel->getActiveSheet()->setCellValue('I7', '=SUM(I10 + I11 + I23)');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(4.5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(9);

        $objPHPExcel->getActiveSheet()->getStyle('A7:A40')->getAlignment()->setWrapText(true);
        /* выравниваем по центру (вертильно-горизонтально) */
        $objPHPExcel->getActiveSheet()
            ->getStyle('A7:A40')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()
            ->getStyle('B7:I40')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('B7:I40')
            ->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->getActiveSheet()
            ->getStyle('A100');

        header('Content-Type: application/vnd.ms-excel');
        $filename = "MyExcelReport_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');


    }

    protected static function listStmt()
    {
        return array(
            '1' => 'Всего обращений, в том числе:',
            '1.1' => 'по телефону "горячей линии"',
            '1.2' => 'по сети "Интернет"',
            '2' => 'Жалобы',
            '3' => 'Заявлений, всего: в т.ч.:',
            '3.1' => 'о выделении средств для оплаты медицинской помощи в рамках территориальной программы государственных гарантий оказания бесплатной медицинской помощи',
            '3.2' => 'о выборе и замене СМО, в том числе:',
            '3.2.1' => 'о выборе СМО',
            '3.2.2' => 'о замене СМО',
            '3.3' => 'ходатайства о регистрации в качестве застрахованного лица',
            '3.4' => 'ходатайства об идентификации в качестве застрахованного лица',
            '3.5' => 'о выдаче дубликата (переоформлении) полиса ОМС, в том числе:',
            '3.5.1' => 'о переоформлении полиса',
            '3.5.2' => 'о выдаче дубликата полиса',
            '3.6' => 'другие,',
            '3.6.1' => 'в том числе по вопросам, не относящиеся к сфере ОМС',
            '4' => 'Обращения за консультацией (разъяснением), в том числе:',
            '4.1' => 'об обеспечении полисами ОМС, в т.ч.:',
            '4.1.1' => 'об обеспечении полисами ОМС иностранных граждан, беженцев',
            '4.2' => 'о выборе МО в сфере ОМС',
            '4.3' => 'о выборе врача',
            '4.4' => 'о выборе или замене СМО',
            '4.5' => 'о организации работы МО',
            '4.6' => 'о санитарно-гигиеническом состоянии МО',
            '4.7' => 'об этике и диентологии медицинских работников',
            '4.8' => 'о КМП',
            '4.9' => 'о лекарственном обеспечении при оказании медицинской помощи',
            '4.10' => 'об отказе в оказании медицинской помощи по программам ОМС',
            '4.11' => 'о получении медицинской помощи по базовой программе ОМС вне территории страхования',
            '4.12' => 'о взимании денежных средств за медицинскую помощь по программам ОМС',
            '4.12.1' => 'о видах, качестве и условиях предоставления медицинской помощи по программам ОМС',
            '4.13' => 'о платах медицинских услугах, оказываемых в ОМС',
            '4.14' => 'другие',
            '5' => 'Предложения'
        );
    }

    protected static function stopList()
    {
        return array(
            'D8', 'G8',
            'C9', 'F9',
            'C11', 'F11',
            'C12', 'F12',
            'C13', 'F13',
            'C14', 'F14',
            'C15', 'F15',
            'C16', 'F16', 'G16', 'H16',
            'C17', 'F17', 'G17', 'H17',
            'C18', 'F18',
            'C19', 'F19',
            'C20', 'F20',
            'C21', 'F21',
            'C22', 'F22'
        );
    }

    protected function currentValue($data, $key, $company, $cell)
    {
        $stop = $this->stopList();
        if(isset($data))
        {
            return in_array($cell, $stop)? 'x': $data[$key];
        }

        return in_array($cell, $stop)? 'x': 0;
    }

    public function actionGetsavefile(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        //   Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $file = $request->path . DIRECTORY_SEPARATOR . $request->file_name;
        //   $file = '/uploads/67/tfoms_logo_120 - копия.gif';
        if (file_exists($file)) {
            //  readfile($file);
            //   return  Yii::$app->response->sendFile($file);
            return file_get_contents($file);
        }
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
//
//        return $config;
//    }
}
