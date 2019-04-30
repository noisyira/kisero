<?php

namespace backend\controllers\user;

use app\models\DispFile;
use backend\models\AnswerScript;
use backend\models\Cdr;
use backend\models\Cel;
use backend\models\Login;
use backend\models\MnCompany;
use backend\models\MnSendStatement;
use backend\models\MnStatement;
use backend\models\MnStatementAction;
use backend\models\MO;
use backend\models\People;
use backend\models\SipAccount;
use backend\models\Stmt;
use backend\models\StmtAction;
use backend\models\StmtAttachment;
use backend\models\StmtCall;
use backend\models\StmtCollection;
use backend\models\StmtDeffered;
use backend\models\StmtTransfer;
use Yii;
use yii\data\ActiveDataProvider;
use yii\debug\models\search\Log;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RestStmtController implements the Rest controller for Stmt model.
 */
class RestStmtController extends Controller
{
    public $modelClass = 'backend\models\Stmt';
    public $client;
    public $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Operators:Cdvzc)s8kfVaAMn&app=dialer';
    public $sipEndpoints = array('rt', 'mypbx');

    protected function verbs()
    {
        return[
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'update' => ['POST', 'PUT'],
            'create' => ['POST'],
            'delete' => ['DELETE'],
            'get-list-stmt' => ['POST'],
            'get-list-users' => ['POST'],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        switch (Login::getTypeUser(Yii::$app->user->id))
        {
            case 1:
                $query = Stmt::find();
                $query->where(['stmt.user_o' => Yii::$app->user->id]);
                $query->andWhere(['NOT IN', 'status', [0]]);
                $query->joinWith(['group', 'theme', 'send', 'call_first', 'stmt_status', 'deffered']);
                $query->orderBy(['id' => SORT_DESC]);
                $query->asArray();
                $query->all();
                break;
            case 2:
                $query = Stmt::find();
                $query->select(['stmt.id']);
//                $query->where(['stmt.company' => Login::companyID(Yii::$app->user->id)]);
//                $query->andWhere(['NOT IN', 'status', [0]]);
                $query->joinWith(['deffered']);
                $query->orderBy(['id' => SORT_DESC]);
                $query->asArray();
                $query->all();
                break;
            default:
                $query = Stmt::find();
                $query->where(['stmt.user_o' => Yii::$app->user->id]);
                $query->andWhere(['NOT IN', 'status', [0]]);
                $query->joinWith(['group', 'theme', 'send', 'call_first', 'stmt_status', 'deffered']);
                $query->orderBy(['id' => SORT_DESC]);
                $query->asArray();
                $query->all();
        }

        return new ActiveDataProvider([
         'query' => $query,
         'pagination' => false
        ]);
    }


    /**
     * Список тем для обращения
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetListStmt()
    {
        return MnStatement::find()->with(['group'])->asArray()->all();
    }

    /**
     * Список операторов для сортировки обращений
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetListUsers()
    {
            switch (Login::getTypeUser(Yii::$app->user->id)) {
                case 1:
                    $user = Login::find()
                        ->where(['id' => Yii::$app->user->id])
                        ->all();
                    break;
                case 2:
                    $user = Login::find()
                        ->where(['company' => Login::companyID(Yii::$app->user->id)])
                        ->all();
                    break;
                default:
                    $user = Login::find()
                        ->where(['id' => Yii::$app->user->id])
                        ->all();
            }

        return $user;
    }

    /**
     * Поиск по реестру застрахованных
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetErzList()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $params = isset($request->data) ? get_object_vars($request->data) : array();

        $model = new People();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = $model->find()
            ->where(array_filter($params))
            ->with(['reestr', 'stik'])
            ->limit(10)
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * Новое обращение
     * @return bool
     */
    public function actionSaveStmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $date =  new \DateTime();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new Stmt();
        $model->attributes =  get_object_vars($request);
    //    $model->statement_date = Yii::$app->formatter->asDatetime($date);
        $model->expired = Yii::$app->formatter->asDate($date->add(new \DateInterval('P30D')));
        $model->company = MnCompany::getOrg()->org->id;
        $model->status = '1';
        $model->user_o = isset($model->user_o) ? $model->user_o : Yii::$app->user->id;
        $model->user_с = Yii::$app->user->id;

        $model->save();

        if($request->deffered)
        {
            $deffered = new StmtDeffered();
            $deffered->attributes = get_object_vars($request->deffered);
            $deffered->active = 0;
            $model->link('deffered', $deffered);
        }

        if( $this->getChannelID() )
        {
            $call = new StmtCall();
            $call->channel_id = $this->getChannelID();
            $call->in_user = Yii::$app->user->id;
            $model->link('call', $call);
        }

        StmtAction::setAction(1,$model, 'Создание обращения');

        return $model;
    }

    /**
     * Редактирование обращений
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGetStmt()
    {
        $request = Yii::$app->request->get();

        switch (Login::getTypeUser(Yii::$app->user->id))
        {
            case 1:
                $model = Stmt::find()
                    ->where(['id' => $request['id']])
                    ->andWhere(['user_o' => Yii::$app->user->id])
                    ->with(['deffered', 'attachment', 'user', 'result', 'group', 'theme', 'call'])
                    ->asArray()
                    ->one();

                if(empty($model))
                    throw new NotFoundHttpException('400');


                $model['uid'] = $this->callRecord($model->call->channelid);

                break;
            case 2:
                $model = Stmt::find()
                    ->where(['id' => $request['id']])
                    ->andWhere(['company' => Login::companyID(Yii::$app->user->id)])
                    ->with(['deffered', 'attachment', 'user', 'result', 'group', 'theme', 'call'])
                    ->asArray()
                    ->one();

                if(empty($model))
                    throw new NotFoundHttpException('400');

                break;
            default:
                $model = Stmt::find()
                    ->where(['id' => $request['id']])
                    ->andWhere(['user_o' => Yii::$app->user->id])
                    ->with(['deffered', 'attachment', 'user', 'result', 'call'])
                    ->asArray()
                    ->one();
                
                if(empty($model))
                    throw new NotFoundHttpException('400');
        }

        $recordPath = null;
        $recordPathA = array();

        if(!empty($model["call"]["callUID"]))
        {
            $c = Login::companyID(Yii::$app->getUser()->id);
            $url = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'incoming' . DIRECTORY_SEPARATOR ;

            $recordFileName = $model["call"]["callUID"]["recordingfile"] . ".mp3";

            $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($url));

            foreach($objects as $object){
                if($recordFileName == $object->getFilename())
                {
                    $recordPath = $object->getpathName();
                    $recordPathA[] = str_replace("/var/www/html/call/backend/web", "", $recordPath);
                    break;
                }
            }
        }

        return array('data' => $model , 'record' => $recordPathA, 'url' => $url );
    }

    public function callRecord($uid)
    {
        $cdr = new Cdr();

        return $cdr->find()->where(
            [   'uniqueid' => $uid,
                'disposition' => 'ANSWERED',
            ])
            ->orderBy([
                'duration' => SORT_DESC
            ])
            ->distinct()
            ->all();
    }

    /**
     * Вернуть обращение на доработку
     * @return null|static
     */
    public function actionReworkStmt()
    {
        $request = Yii::$app->request->get();
        $date =  new \DateTime();

        $stmt = Stmt::findOne($request['id']);
        $stmt->status = "1";
        $stmt->update();

        $action = new StmtAction();
        $action->user_id = Yii::$app->user->id;
        $action->action_date = Yii::$app->formatter->asDatetime($date);
        $action->action = 1013;
        $action->description = "Оператор вернул обращение на доработку";
        $stmt->link('action', $action);

        return $stmt;
    }

    /**
     * Закрытие обращения
     * @return null|static
     * @throws \Exception
     */
    public function actionCloseStmt()
    {
        $model = Yii::$app->request->get('id');
        $date =  new \DateTime();

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        switch ($request->action) {
            case 8:     // Переадресация
                $stmt = Stmt::findOne($model);
                $user_from = $stmt->user_o;

                $stmt->company = Login::companyID($request->user->user_id);
                $stmt->user_o = $request->user->user_id;
                $stmt->update();

                $action = new StmtAction();
                $action->user_id = Yii::$app->user->id;
                $action->action_date = Yii::$app->formatter->asDatetime($date);
                $action->action = $request->action;
                $action->description = $request->msg;
                $stmt->link('action', $action);

                $transfer = new StmtTransfer();
                $transfer->u_from = $user_from;
                $transfer->u_to = $request->user->user_id;
                $stmt->link('transfer', $transfer);

                break;
            case 12:    // Продление сроков обращения
                $stmt = Stmt::findOne($model);
                $stmt->expired = $request->data_expired;
                $stmt->res_msg = $request->msg;

                $action = new StmtAction();
                $action->user_id = Yii::$app->user->id;
                $action->action_date = Yii::$app->formatter->asDatetime($date);
                $action->action = $request->action;
                $action->description = 'Продление сроков по обрпщению с ' .$stmt->expired. ' по ' .$request->data_expired;

                $stmt->update();
                $stmt->link('action', $action);

                break;
            case 1014:  // Удаление обращения
                $stmt = Stmt::findOne($model);
                $stmt->status = "0";
                $stmt->res_msg = $request->msg;
                $stmt->update();

                $action = new StmtAction();
                $action->user_id = Yii::$app->user->id;
                $action->action_date = Yii::$app->formatter->asDatetime($date);
                $action->action = $request->action;
                $action->description = "Удаление обращения";
                $stmt->link('action', $action);
                break;
            default:    // Закрытие обращения
                $stmt = Stmt::findOne($model);

                $stmt->status = ($stmt->tip_statement == 2)? "3" : "2";
                $stmt->res_msg = $request->msg;
                $stmt->plaint = $request->plaint;
                $stmt->cash_back = isset($request->cash_back)?$request->cash_back:null;

                $stmt->scenario = 'close-stmt';
                $stmt->update();

                $action = new StmtAction();
                $action->user_id = Yii::$app->user->id;
                $action->action_date = Yii::$app->formatter->asDatetime($date);
                $action->action = $request->action;
                $action->description = "Закрытие обращения";

                $stmt->link('action', $action);
        }
        return $request->cash;
    }

    /**
     * Обновление данных по обращению
     * @return mixed
     * @throws \Exception
     */
    public function actionUpdateStmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = Stmt::findOne($request->id);

        $params = isset($request) ? get_object_vars($request) : array();
        $d_params = isset($request->deffered) ? get_object_vars($request->deffered) : array();

        $model->attributes = $params;
        $model->update();

        $deffered = StmtDeffered::find()->where(['stmt_id' => $request->id])->one();

        if(!$deffered)
            $deffered = new StmtDeffered;

        $deffered->attributes = $d_params;
        $deffered->active = isset($deffered->active) ? $deffered->active : 0;
        $model->link('deffered', $deffered);

        StmtAction::setAction(1015,$model, 'Обновление обращения');

        return $model;
    }

    /**
     * Список Вид обращений
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetModeStmt()
    {
        return MnSendStatement::find()->asArray()->all();
    }

    /**
     * Список Мед. организаций
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetMoList()
    {
        return MO::find()->asArray()->all();
    }

    /**
     * Список тем обращений
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetThemeStmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $statement = new MnStatement();
        $arr = $statement->find()->where(['group_statement' => $request->data])->asArray()->all();
        $res = array();

        foreach ($arr as $item)
        {
            if($item['gp'] == '-')
                continue;

            if($item['gp'])
                $item['gp'] = MnStatement::findOne([ 'key_statement' => $item['gp'] ])->theme_statement;

            $res[] = $item;
        }
        return $res;
    }
    
    /**
     * Список ответственных пользователей
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetUsersList()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $stmt = Stmt::findOne($request->id);
        $user_o = isset($stmt)?$stmt->user_o:Yii::$app->user->id;

        $users = SipAccount::find()
            ->where(['IN', 'role_type', [1, 2]])
            ->joinWith(['user'])
            ->asArray()
            ->all();

        return array( 'user_o' => $user_o, 'users' => $users );
    }

    /**
     * Список действий при закрытии обращения
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetCloseList()
    {
        return MnStatementAction::find()->where(['type' => 2])->asArray()->all();
    }

    /**
     * Sip данные пользователя
     */
    public function actionGetPjsipAccount()
    {
        $sip = new SipAccount();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $sip->getSipSetting();
    }

    /**
     * Список номеров в телефонной книге
     * @return array|bool
     * @throws \phparia\Exception\InvalidParameterException
     * @throws \phparia\Exception\NotFoundException
     */
    public function actionGetPhoneList()
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

            $resData = ArrayHelper::index($arr, null, 'user.company.name');

            return $resData;
        }

        return false;
    }

    /**
     * Сценарии ответов
     * @return array
     */
    public function actionGetAnswerScript()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = new AnswerScript();
        $answers = $model->find()
            ->where(['key_statement' => $request->key])
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
     * UID разговора
     * @return mixed
     * @throws \phparia\Exception\InvalidParameterException
     * @throws \phparia\Exception\NotFoundException
     */
    public function getChannelID()
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
     * Переадресация обращения
     * @return null|static
     * @throws \Exception
     */
    public function actionTransferStmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $stmt = Stmt::findOne($request->id);
        $from = $stmt->user_o;
        $stmt->user_o = $request->data->user_id;
        $stmt->company = Login::companyID( $request->data->user_id);

        $stmt->update();

        $transfer = new StmtTransfer();
        $transfer->u_from = $from;
        $transfer->u_to = $request->data->user_id;
        $stmt->link('transfer', $transfer);

        return $request;
    }

    /**
     * Формирование отчёта за период
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionReportStmt()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $start = $request->data->startDate;
        $end = $request->data->endDate;

        $userRole = Login::getTypeUser(Yii::$app->user->id);

        return $userRole;
    }

    public function actionDeleteAttachment()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = StmtAttachment::deleteAll(['id' => $request->data->id]);

        $res = StmtAttachment::find()->where(['stmt_id' => $request->data->stmt_id])->asArray()->all();
        return $res;
    }

    public function actionPagi()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $page = $request->page;
        $count = $request->count;
        $sortBy = $request->sortBy;
        $sortOrder = ($request->sortOrder == 'asc')? 'SORT_ASC' : SORT_DESC;

        $query = Stmt::find();

        switch (Login::getTypeUser(Yii::$app->user->id))
        {
        case 1:
            $query->where(['stmt.user_o' => Yii::$app->user->id]);
            $query->andWhere(['NOT IN', 'status', [0]]);
            break;
        case 2:
            $query->where(['stmt.company' => Login::companyID(Yii::$app->user->id)]);
            $query->andWhere(['NOT IN', 'status', [0]]);
            break;
        default:
        }

        $query->andFilterWhere(['like', 'stmt_deffered.fam', $request->filters->fio])
            ->andFilterWhere([ 'stmt.id' => $request->filters->id])
            ->andFilterWhere([ 'stmt.tip_statement' => $request->filters->tip_statement])
            ->andFilterWhere([ 'stmt.user_o' => $request->filters->user_o])
            ->andFilterWhere([ 'stmt.form_statement' => $request->filters->form_statement])
            ->andFilterWhere([ 'stmt.theme_statement' => $request->filters->theme_statement])
            ->andFilterWhere([ 'stmt.mo' => $request->filters->mo]);

        $query->joinWith(['group', 'theme', 'send', 'call_first', 'stmt_status', 'deffered', 'call']);
        $query->orderBy(['id' => SORT_DESC]);
        $query->limit($count);
        $query->offset( ($page * $count) - $count);
        $query->orderBy([$sortBy => $sortOrder]);

        $items = Stmt::find();

        switch (Login::getTypeUser(Yii::$app->user->id))
        {
            case 1:
                $items->where(['stmt.user_o' => Yii::$app->user->id]);
                $items->andWhere(['NOT IN', 'status', [0]]);
                break;
            case 2:
                $items->where(['stmt.company' => Login::companyID(Yii::$app->user->id)]);
                $items->andWhere(['NOT IN', 'status', [0]]);
                break;
            default:
        }

            $items->joinWith(['group', 'theme', 'send', 'call_first', 'stmt_status', 'deffered']);
            $items->andFilterWhere(['like', 'stmt_deffered.fam', $request->filters->fio])
                ->andFilterWhere([ 'stmt.id' => $request->filters->id])
                ->andFilterWhere([ 'stmt.tip_statement' => $request->filters->tip_statement])
                ->andFilterWhere([ 'stmt.user_o' => $request->filters->user_o])
                ->andFilterWhere([ 'stmt.form_statement' => $request->filters->form_statement])
                ->andFilterWhere([ 'stmt.theme_statement' => $request->filters->theme_statement])
                ->andFilterWhere([ 'stmt.mo' => $request->filters->mo]);

        $data = array(
            'rows' => $query->asArray()->all(),
            'pagination' => [
                'count' => $count,
                //'page' => 5,
                'pages' => 7,
                'size' => $items->count()
            ],
            'login' => Login::getTypeUser(Yii::$app->user->id)
        );

        return  array( 'data' => $data );
    }

    /**
     * Список загруженных файлов
     */
    public function actionInteractionFiles()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $page = $request->page;
        $count = $request->count;
        $sortBy = $request->sortBy;
        $sortOrder = ($request->sortOrder == 'asc')? 'SORT_ASC' : SORT_DESC;

        $query = DispFile::find();
        $query->limit($count);
        $query->offset( ($page * $count) - $count);
        $query->orderBy([$sortBy => $sortOrder]);
   //     $files->orderBy(['dt' => SORT_DESC]);

        $data = array(
            'rows' => $query->asArray()->all(),
            'pagination' => [
                //  'count' => 10,
                //   'page' => 5,
                'pages' => 7,
                'size' => DispFile::find()->count()
            ]
        );

        Yii::$app->response->format = Response::FORMAT_JSON;

        return  array( 'data' => $data, 'res' => $request );
    }

    public function actionStmtXml()
    {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $files = StmtAttachment::find();
    //$files->limit(10);

    foreach ( $files->all() as $item)
    {
        //$arr[] =  $item->path . DIRECTORY_SEPARATOR . $item->file_name;

        if (!file_exists($item->path . DIRECTORY_SEPARATOR . $item->file_name)) {
            $arr[] =  $item->id;
        }
    }

    return  $arr;
    }
}
