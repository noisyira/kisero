<?php

namespace backend\controllers;

use app\models\Astdb;
use app\models\Monitoring;
use app\models\PollQuestion;
use backend\models\Dial;
use backend\models\DialPeople;
use app\models\Poll;
use backend\models\AnswerScript;
use backend\models\Cdr;
use backend\models\Cel;
use backend\models\Kladr;
use backend\models\Login;
use backend\models\MnCompany;
use backend\models\MnStatement;
use backend\models\MO;
use backend\models\People;
use backend\models\PeopleSearch;
use backend\models\PollAnswers;
use backend\models\PollList;
use backend\models\PollPeople;
use backend\models\Reestr;
use backend\models\SipAccount;
use backend\models\Stmt;
use backend\models\StmtAction;
use backend\models\StmtAdmin;
use backend\models\StmtAttachment;
use backend\models\StmtCall;
use backend\models\StmtDeffered;
use backend\models\StmtMonitoring;
use backend\models\StmtSearch;
use backend\models\StmtSearchArchive;
use backend\models\UploadForm;

use backend\services\Sstu;
use Yii;
use backend\models\Statement;
use backend\models\StatementSearch;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModeratorController implements the CRUD actions for Statement model.
 */
class ModeratorController extends Controller
{
    public $layout = 'moderation';
    public $client;
    public $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Denis:KYlWXL1i4-bZmrr&app=dialer';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Statement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StmtSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $ariAddress = $this->ariAddress;
//      $amiAddress = 'ami:123@192.168.1.153:5038';
        $logger = new \Zend\Log\Logger();
        $logWriter = new \Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($logWriter);
        $filter = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::NOTICE);
        $logWriter->addFilter($filter);

        $this->client = new \phparia\Client\Phparia($logger);
        $this->client->connect($ariAddress);

        //$this->sendAuth(555, 'f4H3V7Ug');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

    /**
     * Displays a single Statement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model_atch = new StmtAttachment();
        $upload = new UploadForm();

        $model = Stmt::find()
            ->where(['id' => $id])
            ->with(['group', 'theme', 'send', 'org', 'call', 'stage', 'allactions', 'stmt_status', 'deffered', 'attachment'])
            ->one();

        $cdr = new Cdr();
        $cdr_data = $cdr->find()->where(
            ['uniqueid' => $model->call->channel_id,
                'disposition' => 'ANSWERED',
            ])
            ->orderBy([
                'duration' => SORT_DESC
            ])
            ->distinct()
            ->all();

        $sstuData = Yii::$app->request->post('sstu');

        if( !empty($sstuData) )
        {
            $sstu = new Sstu(array($sstuData));
        }

        $cel = new Cel();
        $cel_data = $cel->find()->where(['uniqueid' => $model->call->channel_id])->all();
        $request = Yii::$app->request;

        if ($request->post('closeStmt')) {
            if ($request->post('statusStmt')) {
                switch ($request->post('statusStmt')) {
                    case 3:     // Закрыть обращение
                        Stmt::closeStmt(Yii::$app->request->get('id'), $request->post('statusStmt'), '', $request->post('Stmt'));
                        StmtAction::createAction(Yii::$app->request->get('id'), Yii::$app->user->id, 4, 'Администратор контакт-центра закрыл обращение');
                        try{
                            if( !empty($sstuData) )
                            {
                                $sstu->SendSstu();
                            }
                        } catch (\Exception $ex) {

                        }
                        break;
                    case 2:     // Продлить сроки рассмотрения
                        Stmt::closeStmt(Yii::$app->request->get('id'), '1', $_POST['Stmt']['expired']);
                        StmtAction::createAction(Yii::$app->request->get('id'), Yii::$app->user->id, 12, 'Сроки рассмотрения продленны');
                        break;
                    case 1:     // Вернуть статус «В работе»
                        Stmt::returnStmt(Yii::$app->request->get('id'), $request->post('statusStmt'), $_POST['Stmt']['user_o']);
                        StmtAction::createAction(Yii::$app->request->get('id'), Yii::$app->user->id, 13, 'Администратор контакт-центра вернул статус «В работе»');
                        break;
                }
            }

            return $this->redirect('index');
        }

        if ($model_atch->load(Yii::$app->request->post())) {
            $upload->file = UploadedFile::getInstances($model_atch, 'file_name');
            foreach ($upload->file as $file) {
                $path = Yii::getAlias('@backend/uploads/') . $id;
                if (!file_exists($path)) {
                    mkdir($path, 0700);
                }

                if (StmtAttachment::createAttch($id, $file->name, $file->extension, $path))
                    $file->saveAs($path . '/' . $file->name);
            }
            return $this->redirect('');
        }

        return $this->render('view', [
            'model' => $model,
            'cdr' => $cdr_data,
            'cel' => $cel_data
        ]);
    }

    /**
     * Creates a new Statement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Statement();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Statement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Statement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Statement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Statement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stmt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all Statement models.
     * @return mixed
     */
    public function actionCreateStmt()
    {
        $model = new Stmt();
        $def = new StmtDeffered();
        $model_atch = new StmtAttachment();
        $upload = new UploadForm();
        $erz = new People();

        if (Yii::$app->request->isAjax && $erz->load(Yii::$app->request->post())) {

            $data = $erz->find()
                ->where(array_filter($erz->attributes))
                ->with(['reestr', 'stik'])
                ->limit(10)
                ->all();

            return $this->render('create/index', [
                'time' => date('H:i:s'),
                'data' => $data,
                'model' => $model,
                'deffered' => $def,
                'attch' => $model_atch,
                'erz' => $erz
            ]);
            //  return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post()) && $model_atch->load(Yii::$app->request->post()) && $def->load(Yii::$app->request->post())) {
            $model->anon_user = Yii::$app->request->post('Stmt')['anon_user'];
            $res = $model->createStmt();

            /* Информация обратившегося */
            if ($res) {
                $def->stmt_id = $res->id;
                $def->name_okato = ($def->name_okato == 'Место рождения') ? null : $def->name_okato;
                $def->save();
            }

            /* Действие пользователя принявшего обращение */
            if ($res) {
                StmtAction::createAction($res->id, Yii::$app->user->id, '1');
            }

            $stmt_id = $res->id;
            $upload->file = UploadedFile::getInstances($model_atch, 'file_name');

            foreach ($upload->file as $file) {
                $path = Yii::getAlias('@backend/uploads/') . $stmt_id;
                if (!file_exists($path)) {
                    mkdir($path, 0700);
                }

                if (StmtAttachment::createAttch($stmt_id, $file->name, $file->extension, $path, $model_atch))
                    $file->saveAs($path . '/' . $file->name);
            }

            return $this->redirect(['index']);
        } else {

            return $this->render('create/index', [
                'model' => $model,
                'deffered' => $def,
                'attch' => $model_atch,
                'erz' => $erz
            ]);
        }
    }

    /**
     * depended dropdown list
     */
    public function actionTheme()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = MnStatement::getOptionsbyProvince($cat_id);

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }


    /**
     * depended dropdown list
     */
    public function actionMo()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = MO::getOptionsbyProvince($cat_id);

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Кладр city
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionCity($q = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '', 'data' => '']];

        if (!is_null($q)) {
            $model = Kladr::find()
                ->select(['OKATO as id', 'NAME as text', 'SOCR'])
                ->where(['LIKE', 'NAME', $q])
                ->andWhere(['IN', 'LEVELID', [3, 4]])
                ->limit(20)
                ->asArray()
                ->all();

            $out['results'] = $model;
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Kladr::find()->where(['OKATO' => $id])->one()->NAME, ''];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'Not matching records found', ''];
        }
        return $out;
    }

    public function actionOkato($q = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '', 'data' => '']];

        if (!is_null($q)) {
            $model = Kladr::find()
                ->select(['OKATO as id', 'NAME as text', 'SOCR'])
                ->where(['LIKE', 'NAME', $q])
                ->andWhere(['IN', 'LEVELID', [1, 2, 3]])
                ->andWhere(['CC' => 26])
                ->limit(20)
                ->asArray()
                ->all();

            $out['results'] = $model;
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Kladr::find()->where(['OKATO' => $id])->one()->NAME, ''];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'Not matching records found', ''];
        }
        return $out;
    }

    public function actionAnswerScript()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AnswerScript::find()->with(['stmt']),
            'sort' => false
        ]);

        return $this->render('answer/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAnswerCreate()
    {
        $model = new AnswerScript();

        if ($model->load(Yii::$app->request->post())) {

            $model->recomend_users = json_encode(Yii::$app->request->post()['AnswerScript']['recomend_users']);

            if ($model->validate() && $model->save()) {
                return $this->actionAnswerScript();
            }

            return $this->render('answer/create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('answer/create', [
                'model' => $model,
            ]);
        }
    }

    public function actionAnswerView($id)
    {
        return $this->render('answer/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing AnswerScript model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAnswerUpdate($id)
    {
        $model = $this->findModelAnswer($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->recomend_users = json_encode(Yii::$app->request->post()['AnswerScript']['recomend_users']);

            if ($model->validate() && $model->save())
                return $this->redirect(['moderator/answer-script']);

            return $this->redirect(['moderator/answer-script']);
        } else {
            return $this->render('answer/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the AnswerScript model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AnswerScript the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAnswer($id)
    {
        if (($model = AnswerScript::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function emptyVal($value = null)
    {
        if (!isset($value) && empty($value)) {
            return '<em class="em-title">(Не указано)</em>';
        }
        return $value;
    }

    public static function emptyDate($value = null)
    {
        if (!isset($value) && empty($value)) {
            return '<em class="em-title">(Не указано)</em>';
        }
        return Yii::$app->formatter->asDate($value, 'dd-MM-yyyy');
    }

    public function actionArchive()
    {
        $searchModel = new StmtSearchArchive();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('archive/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaveFile()
    {
        $model = StmtAttachment::findOne(Yii::$app->request->get('id'));

        $file = $model->path . DIRECTORY_SEPARATOR . $model->file_name;

        if (file_exists($file)) {
//            Yii::$app->response->xSendFile($file);
            return \Yii::$app->response->sendFile($file);
        }

        return $this->goBack();
    }

    public function actionReport()
    {
        $searchModel = new StmtSearchArchive();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(!$searchModel->statement_date)
            $searchModel->statement_date = $this->getIntervalKv(date('now'));

        $rp = Yii::$app->request->post();

        if (isset($rp['download'])) {
            Stmt::saveReport($searchModel, $searchModel->statement_date);
        }

        if (!empty(Yii::$app->request->post('plaint'))) {
           Stmt::saveReportPlaints($searchModel->statement_date);
        }

        return $this->render('report/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getIntervalKv($d){
        $kv = (int)((date('n', $d)-1)/3+1);
        $year = date('y');

        $st = date('d-m-Y',mktime(0,0,0,1,1,$year));
        $en = date('d-m-Y',mktime(0,0,0,($kv)*3+1,0,$year));
        return $st .' - '. $en;
    }

    public function actionMonitoring()
    {
        $model = new Monitoring();

        if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->post('save') ) {
            if($model::findOne(['range' =>  $model->range]))
            {
                $model->update();
            } else {
                $model->save();
            }
            \Yii::$app->getSession()->setFlash('success', 'Сохраненно..');
        }

        if(Yii::$app->request->post('Monitoring'))
        {
            $model->range = Yii::$app->request->post('Monitoring')['range'];
            $model->text = $this->renderPartial('monitoring/_tmpl');
        }

        if(Yii::$app->request->post('range'))
        {
            $model->range = Yii::$app->request->post('range');
            $model->text = $this->renderPartial('monitoring/_tmpl');
        }

        return $this->render('monitoring/index', [
            'model' => $model,
        ]);
    }

    public function actionHistory()
    {
        $query = Monitoring::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('monitoring/history', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($params)
    {
        $searchModel = new StmtSearchArchive();
        $report = new Stmt();

        $model = $searchModel->searchReportTotal($params)->getModels();
        $all = $searchModel->searchReportAll($params)->getModels();
        $calls = $searchModel->searchReportTotalCalls($params)->getModels();
        $internet = $searchModel->searchReportTotalInternet($params)->getModels();
        $plaints = $searchModel->searchReportTotalPlaint($params)->getModels();

        $model = ArrayHelper::index($model, 'k');
        $model['1'] = array_shift($all);
        $model['1.1'] = array_shift($calls);
        $model['1.2'] = array_shift($internet);
        $model['2'] = array_shift($plaints);

        return $report->generateExcelReport($model);
    }

    /**
     * Диспанцеризация
     * @return string
     */
    public function actionClinicalExamination()
    {
        $sql = DialPeople::find();
        $dataProvider = array();

        if (!empty(Yii::$app->request->post('clinical'))) {
            $dataProvider = new ActiveDataProvider([
                'query' => $sql,
                'pagination' => array('pageSize' => 50),
                'sort' => false
            ]);
        }

//        $sql = DialPeople::find()->select('pid');
//        $sql_success = DialPeople::find()->select('pid')->where(['IN', 'result', ['1'] ]);
//        $sql_range = DialPeople::find()->select('pid')->where(['IN', 'result', ['2'] ]);
//        $sql_cancel = DialPeople::find()->select('pid')->where(['IN', 'result', ['3, 4, 11'] ]);


        $total = [
//            'all' => Dial::find()->count(),
//            'all_call' => DialPeople::find()->count(),
//            'all_success' => DialPeople::find()->where(['IN', 'result', ['1'] ])->count(),
//            'all_range' => DialPeople::find()->where(['IN', 'result', ['2'] ])->count(),
//            'all_cancel' => DialPeople::find()->where(['IN', 'result', ['3, 4, 11'] ])->count(),
//            'ingos' => Dial::find()->where(['smo' => '26005'])->count(),
//            'ingos_call' => Dial::find()->where(['smo' => '26005'])->andWhere(['IN', 'id', $sql])->count(),
//            'ingos_success' => Dial::find()->where(['smo' => '26005'])->andWhere(['IN', 'id', $sql_success])->count(),
//            'ingos_range' => Dial::find()->where(['smo' => '26005'])->andWhere(['IN', 'id', $sql_range])->count(),
//            'ingos_cancel' => Dial::find()->where(['smo' => '26005'])->andWhere(['IN', 'id', $sql_cancel])->count(),
//            'vtb' => Dial::find()->where(['smo' => '26002'])->count(),
//            'vtb_call' => Dial::find()->where(['smo' => '26002'])->andWhere(['IN', 'id', $sql])->count(),
//            'vtb_success' => Dial::find()->where(['smo' => '26002'])->andWhere(['IN', 'id', $sql_success])->count(),
//            'vtb_range' => Dial::find()->where(['smo' => '26002'])->andWhere(['IN', 'id', $sql_range])->count(),
//            'vtb_cancel' => Dial::find()->where(['smo' => '26002'])->andWhere(['IN', 'id', $sql_cancel])->count(),
        ];

      //  echo '<pre>'; print_r($total);

        return $this->render('poll/clinical_examination', [
            'dataProvider' => $dataProvider,
            'total' => $total,
            'model' => new DialPeople()
        ]);
    }

    /**
     * Текущие опросы
     * @return string
     */
    public function actionPoll()
    {
        $sql = PollList::find()
            ->joinWith(['name']);

        $dataProvider = new ActiveDataProvider([
            'query' => $sql,
            'pagination' => array('pageSize' => 10),
            'sort' => false
        ]);

        return $this->render('poll/index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Создание опроса
     * @return string|Response
     */
    public function actionPollCreate()
    {
        $searchModel = new PeopleSearch();
        $model = new PollList();

        /* Поиск людей для опроса */
        if (Yii::$app->request->post() && empty(Yii::$app->request->post('save'))) {
            $dataProvider = $searchModel->search(Yii::$app->request->post());
        }

        /* Сохранение фильтра поиска */
        if (Yii::$app->request->post('PeopleSearch')) {
            $searchModel->attributes = Yii::$app->request->post('PeopleSearch');
            $model->attributes = Yii::$app->request->post('PollList');
        }

        /* Сохранение опроса */
        if (Yii::$app->request->post('save')) {
            $list = !empty(Yii::$app->request->post('list')) ? json_decode(Yii::$app->request->post('list'), true) : $searchModel->search(Yii::$app->request->post())->getKeys();
            $poll = Yii::$app->request->post('PollList');

            if ($list) {

                $model->attributes = $poll;

                if ($model->poll_start)
                    $model->poll_start = Yii::$app->formatter->asDatetime($poll['poll_start']);

                if ($model->poll_end)
                    $model->poll_end = Yii::$app->formatter->asDatetime($poll['poll_end']);

                if ($model->save()) {
                    foreach ($list as $item) {
                        $pollPeople = new PollPeople();
                        $pollPeople->enp = People::findOne($item)->ENP;
                        $pollPeople->company = ($poll['tfoms'] == 1) ? '1' : People::getSmoID($item);
                        $pollPeople->poll_id = $poll['poll_id'];

                        $model->link('poll', $pollPeople);
                    }

                    return $this->redirect('poll');
                }
            }
        }

        return $this->render('poll/create', [
            'searchModel' => $searchModel,
            'dataProvider' => isset($dataProvider) ? $dataProvider : null,
            'model' => $model
        ]);
    }

    /**
     * Начало опроса
     * @return Response
     */
    public function actionStart()
    {
        $id = Yii::$app->request->get('id');

        $poll = PollList::findOne($id);
        $poll->status = '1';
        $poll->update();

        return $this->redirect('poll');
    }

    /**
     * Завершение опроса
     * @return Response
     */
    public function actionClose()
    {
        $id = Yii::$app->request->get('id');

        $poll = PollList::findOne($id);
        $poll->status = '2';
        $poll->update();

        return $this->redirect('poll');
    }

    /**
     * опрос детально (по каждому)
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        $company = new PollPeople();

        $list = PollAnswers::find()
            ->select(['question_key', 'answer_key', 'COUNT(id) as count'])
            ->with(['question'])
            ->where(['poll_id' => $id])
            ->groupBy(['question_key', 'answer_key'])
            ->asArray()
            ->all();

        $ar_sort = array_map(
            function($n)
            {
                return $n['question']['order_list'];        //Выбираем поле, по которому будем сортировать массив
            },
            $list
        );
        array_multisort($ar_sort, SORT_ASC, $list);

        $count = PollAnswers::find()->select(['people_id'])->where(['poll_id' => $id])->groupBy(['people_id'])->count();

        return $this->render('poll/detail', [
            'company' => ArrayHelper::index($company->reportCompany($id), 'status', 'org.name'),
            'list' => ArrayHelper::index($list, 'answer_key', 'question_key'),
            'count' => $count
        ]);
    }

    public function actionGetPoll()
    {
        $model = 'asdas';

        return $this->renderAjax('get-poll', [
            'model' => $model,
        ]);
    }


    public function actionClosePeople($smo)
    {
        return $this->render('poll/close');
    }

    /**
     * Сохранить отчёт по опросу
     */
    public function actionSaveExcel($id)
    {
        $list = PollAnswers::find()
            ->select(['question_key', 'answer_key', 'COUNT(id) as count'])
            ->with(['question'])
            ->where(['poll_id' => $id])
            ->groupBy(['question_key', 'answer_key'])
            ->asArray()
            ->all();

        $data = PollList::find()->where(['id' => $id])->with(['name'])->one();

        $ar_sort = array_map(
            function($n)
            {
                return $n['question']['order_list'];        //Выбираем поле, по которому будем сортировать массив
            },
            $list
        );
        array_multisort($ar_sort, SORT_ASC, $list);

        $list = ArrayHelper::index($list, 'answer_key', 'question_key');

        $count = PollAnswers::find()->select(['people_id'])->where(['poll_id' => $id])->groupBy(['people_id'])->count();
        $VTBcount =PollAnswers::listAllCount($id, 2);
        $INGOScount = PollAnswers::listAllCount($id, 3);
        $poll = PollList::find()->with(['name'])->where(['id' => $id])->one();
        $nameReport = isset($poll->name->name)? str_replace(' ', '_', $poll->name->name) : 'Отчёт';

        $objPHPExcel = new \PHPExcel();
        $objWorkSheet = $objPHPExcel->setActiveSheetIndex(0);

        $i = 1;

        $objWorkSheet->setCellValue('A'.$i, $data->name->name);
        $objWorkSheet->mergeCells('A'.$i.':C'.$i);
        $i++;

        if( !empty($data->poll_start) && !empty($data->poll_end) )
        {
            $objWorkSheet->setCellValue('A'.$i, 'Период:');
            $objWorkSheet->setCellValue('B'.$i, $data->poll_start);
            $objWorkSheet->setCellValue('C'.$i, $data->poll_end);
            $i++;
        }
        $i++;

        $objWorkSheet->setCellValue('A'.$i, 'Количество опрошенных: '. $count);
        $objWorkSheet->mergeCells('A'.$i.':C'.$i);

        if(count(PollAnswers::listC($id)) > 1)
        {
            $objWorkSheet->setCellValue('D'.$i, 'ВТБ: '. $VTBcount);
            $objWorkSheet->mergeCells('D'.$i.':E'.$i);

            $objWorkSheet->setCellValue('F'.$i, 'ИНГОССТРАХ: '. $INGOScount);
            $objWorkSheet->mergeCells('F'.$i.':G'.$i);
        }

        $i++;

        $objWorkSheet->getColumnDimension('A')->setAutoSize(true);
        $objWorkSheet->getColumnDimension('B')->setAutoSize(true);
        $objWorkSheet->getColumnDimension('C')->setAutoSize(true);

        foreach ($list as $k => $data)
        {
            $objWorkSheet->setCellValue('A'.$i, PollQuestion::findOne($k)->value);
            $objWorkSheet->getStyle('A'.$i)->getAlignment()->setWrapText(true);
            $objWorkSheet->getRowDimension($i)->setRowHeight($this->getRowcount(PollQuestion::findOne($k)->value) * 12.75 + 5.25);
            /* объединение ячеек */
            $objWorkSheet->mergeCells('A'.$i.':G'.$i);
            $i++;

            foreach ($data as $item)
            {
                $objWorkSheet->setCellValue('A'.$i, PollQuestion::answerName($item['answer_key']));
                $objWorkSheet->setCellValue('B'.$i, $item['count'] );
                $objWorkSheet->setCellValue('C'.$i, number_format(($item['count'] / $count) * 100, 2, '.', ' ') . ' %');

                if(count(PollAnswers::listC($id)) > 1)
                {
                    $objWorkSheet->setCellValue('D'.$i, PollAnswers::listCount($id, $item['question_key'], $item['answer_key'], 2));
                    $objWorkSheet->setCellValue('E'.$i, number_format( (PollAnswers::listCount($id, $item['question_key'], $item['answer_key'], 2) / $VTBcount) * 100, 2, '.', ' ' ) . '%'  );
                    $objWorkSheet->setCellValue('F'.$i, PollAnswers::listCount($id, $item['question_key'], $item['answer_key'], 3));
                    $objWorkSheet->setCellValue('G'.$i, number_format( (PollAnswers::listCount($id, $item['question_key'], $item['answer_key'], 3) / $INGOScount) * 100, 2, '.', ' ' ) . '%'  );
                }
                $i++;
            }
        }

        $filename = $nameReport.'_'.date("d-m-Y").".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    protected function getRowcount($text, $width=55) {
        $rc = 0;
        $line = explode("\n", $text);
        foreach($line as $source) {
            $rc += intval((strlen($source) / $width) +1);
        }
        return $rc;
    }

    public function actionSstu()
    {
        $searchModel = new StmtSearch();
        $dataProvider = $searchModel->searchSstu(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('selection'))
        {
            $s = new Sstu(Yii::$app->request->post('selection'));
            $s->SstuArchive();
        }

        return $this->render('sstu/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSstuReport()
    {
        $sql = "SELECT YEAR(s.statement_date) AS 'Year', MONTH(s.statement_date) AS 'MonthNum', DATENAME(month, s.statement_date) AS 'Month', COUNT(*) AS 'Total' 
            FROM stmt s
                RIGHT JOIN stmt_sstu su ON s.id = su.stmt_id
            GROUP BY YEAR(s.statement_date), DATENAME(month, s.statement_date), MONTH(s.statement_date)
        ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'sort' => [
                'defaultOrder' => ['Year' => SORT_ASC, 'MonthNum' => SORT_ASC],
                'attributes' => [
                    'Year',
                    'MonthNum'
                ]
            ]
        ]);

        return $this->render('sstu/report', [
            'dataProvider' => $dataProvider
        ]);
    }
}