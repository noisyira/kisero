<?php

namespace backend\controllers;

use app\models\Sip;
use app\models\SipOrg;
use backend\models\AuthAssignment;
use backend\models\Cdr;
use backend\models\Dial;
use backend\models\DialPeople;
use backend\models\DispContent;
use backend\models\MnCompanySub;
use backend\models\MnStatement;
use backend\models\MO;
use backend\models\SipSetting;
use backend\models\SipAccount;
use backend\models\AuthItem;
use backend\models\Statement;
use backend\models\Stmt;
use backend\models\StmtSearchArchive;
use backend\models\Useroptions;
use Yii;
use backend\models\Login;
use backend\models\LoginSearch;
use yii\data\ActiveDataProvider;
use yii\db\Connection;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * AdminSecurityController implements the CRUD actions for Login model.
 */
class AdminSecurityController extends Controller
{
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
     * Lists all Login models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $login = Login::find()->select(['login.id', 'company', 'sip_private_identity'])->joinWith('user')->where(['NOT', ['sip_private_identity' => NULL]])->asArray()->all();
//        $data  = SipOrg::find()->asArray()->all();
//
//        foreach ($login as $v)
//        {
//            if(!SipOrg::findOne(['sip' => $v['sip_private_identity']]))
//            {
//                switch ($v['company']) {
//                    case 1:
//                        $c_name = 'TFOMS';
//                        break;
//                    case 2:
//                        $c_name = 'VTB';
//                        break;
//                    case 3:
//                        $c_name = 'IGS';
//                        break;
//                }
//
//                $data = new SipOrg();
//                $data->name = $c_name;
//                $data->sip = $v['sip_private_identity'];
//                $data->org = $v['company'];
//
//                $data->save();
//
//
//            }
//        }

        $searchModel = new LoginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /* Отправка Auth */
    public function sendAuth($id)
    {
        /* Отправка Auth */
        $data = array(
            array('attribute' => 'auth_type', 'value' => 'userpass'),
            array('attribute' => 'username', 'value' => "$id"),
            array('attribute' => 'password', 'value' => "$id"),
        );

        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/auth/".$id."?api_key=Denis:123";

        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "fields"=> $data ) );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        if($result)
            $this->sendAor($id);
    }

    /* Отправка Aor */
    public function sendAor($id)
    {
        $data = array(
            array('attribute' => 'support_path', 'value' => 'yes'),
            array('attribute' => 'remove_existing', 'value' => 'false'),
            array('attribute' => 'max_contacts', 'value' => '2'),
            //         array('attribute' => 'contact', 'value' => 'sip:'.$id.'@192.168.5.62:5062'),
        );

        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/aor/".$id."?api_key=Denis:123";

        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "fields"=> $data ) );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);

        if($result)
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
            array('attribute' => 'callerid', 'value' => ''.$id.' <'.$id.'>'),
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

        $url = "http://192.168.1.47:8088/ari/asterisk/config/dynamic/res_pjsip/endpoint/".$id."?api_key=Denis:123";

        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode( array( "fields"=> $data ) );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);

       // echo '<pre>'; print_r($result);
    }
    
    /**
     * Displays a single Login model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionReport($id) {
        $this->layout = 'pdf';
        // get your HTML raw content without any layouts or scripts
        $data = new Login();
        $user = $data->find()->where(['id' => $id])->with(['user'])->one();
        $fileName = $user->username."_".$user->fam."_".$user->im."_".$user->ot;

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // the output PDF filename.
            'filename' => $fileName.".pdf",
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $this->render('layots/viewpdf', ['user'=>$user]),
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['Авторизация контакт-центр'],
            ]
        ]);

        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionBlock($id)
    {
        $user = Login::findOne($id);

        $user->block = 1;

        if( $user->save() )
        {
            return $this->redirect('index');
        }

        return $this->redirect('index');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionUnblock($id)
    {
        $user = Login::findOne($id);

        $user->block = 0;

        if( $user->save() )
        {
            return $this->redirect('index');
        }

        return $this->redirect('index');
    }


    /**
     * Creates a new Login model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Login();
        $sip = new SipAccount();

        $type = Yii::$app->authManager;
        $type_roles = $type->getRoles();

        $items = ArrayHelper::map($type_roles,'name','name');
      
        if ($model->load(Yii::$app->request->post()) ) {

            $userID = $model->registration();
            if($userID)
            {
                AuthAssignment::createAuthAssignment($userID->id, $model->type);
            }

            if( $sip->load(Yii::$app->request->post()) )
            {
                if($sip->create($userID) && $sip->createSIP == 1)
                {   return $this->redirect(['admin-security/index']);   }
            }

            return $this->redirect(['admin-security/index']);

        } else {
            return $this->render('create', [
                'model' => $model,
                'items' => $items,
                'sip' => $sip
            ]);
        }
    }

    /**
     * Updates an existing Login model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sip = SipAccount::find()
            ->where(['user_id' => $id])
            ->one();

        $type = Yii::$app->authManager;
        $type_roles = $type->getRoles();

        $items = ArrayHelper::map($type_roles,'name','name');

        if ($model->load(Yii::$app->request->post()) && $sip->load(Yii::$app->request->post())) {

            $model->password = Yii::$app->security->generatePasswordHash($model->secret);
            $model->save();

            if($sip)
            {
                $sip->save();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'items' => $items,
                'sip' => $sip
            ]);
        }
    }

    /**
     * Филиалы и доп. офисы
     * depended dropdown list
     */
    public function actionTheme() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = MnCompanySub::getSubCompany($cat_id);

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Deletes an existing Login model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $sipID = '';
        $this->findModel($id)->delete();

        $sip = SipAccount::find()->where(['user_id' => $id])->one();
        if($sip){
            $sipID = $sip->sip_private_identity;
            $sip->delete();
        }

        $auth = AuthAssignment::find()->where(['user_id' => $id])->one();
        if($auth)
            $auth->deleteAll(['user_id' => $id]);

        $user = Useroptions::find()->where(['extension' => $sipID])->one();
        if($user)
            $user->delete();

        SipAccount::deleteEndpoint($sipID, "auth");
        SipAccount::deleteEndpoint($sipID, "aor");
        SipAccount::deleteEndpoint($sipID, "endpoint");

        return $this->redirect(['index']);
    }

    /**
     * Finds the Login model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Login the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Login::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * настройки SIP
     */
    public function actionSipSetting()
    {
        $model = new SipSetting();

        $dataProvider = $model->find()->one();

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            $dataProvider = $model->find()->one();
            return $this->render('setting/update', [
                'model' => $dataProvider,
            ]);
        }

        if(!$dataProvider)
        {
            return $this->render('setting/create', [
                'model' => $model,
            ]);
        }
        return $this->render('setting/update', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Страница добавления/редактирования «Темы обращения»
     */
    public function actionThemeStatement()
    {
        $model = new MnStatement();

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            $dataProvider = $model->find()->all();
            return $this->render('statement/index', [
                'dp' => $dataProvider,
                'model' => $model,
            ]);
        }

        return $this->render('statement/index', [
            'model' => $model,
        ]);
    }
    
    
    public function actionAllViewRecords()
    {
        $model = new Cdr();

        $data = $model->find()
            ->where(['disposition' => 'ANSWERED'])
            ->andWhere(['IN', 'lastapp', ['Playback', 'Queue'] ])
            ->andWhere(['not', ['recordingfile' => null]])
            ->andWhere('duration>:duration', [':duration' => 10 ])
            ->orderBy(['calldate' => SORT_DESC])
            ->groupBy('recordingfile');

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
        ]);
        
        return $this->render('records/index', [
            'data' => $dataProvider
        ]);
    }
}
