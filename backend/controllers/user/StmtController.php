<?php

namespace backend\controllers\user;

use app\models\DialParams;
use app\models\DispFile;
use app\models\Monitoring;
use app\models\PeopleTest;
use app\models\PeopleView;
use backend\components\Helpers;
use backend\controllers\ModeratorController;
use backend\models\DispContent;
use backend\models\Login;
use backend\models\ReportIntraservice;
use backend\models\SipAccount;
use backend\models\Stmt;
use backend\models\StmtAttachment;
use backend\models\StmtRoszdravnadzor;
use backend\models\StmtSearchArchive;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * StmtController implements the CRUD actions for Stmt model.
 */
class StmtController extends Controller
{
    public $layout = 'user-sip';

    public function beforeAction($action)
    {
        if (in_array($action->id, ['file', 'interaction', 'save-dial-people'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Display main page.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));

        $first = array("Оператор 1-го уровня", "Страховой представитель 1-го уровня");
        $second = array("Оператор 2-го уровня", "Оператор 2-го уровня с правами администратора",
            "Страховой представитель 2-го уровня", "Страховой представитель 2-го уровня (администратор)",
            "Страховой представитель 3-го уровня");

        switch ($role) {
            case in_array($role, $first):
                $route =  Helpers::routeOperatorFirst();
                break;
            case in_array($role, $second):
                $route =  Helpers::routeOperatorSecond();
                break;

            default:
                $route =  Helpers::routeOperatorSecond();
                break;
        }


        return $this->render('main', [
            'route' => $route,
            'roszdravnadzor' => new StmtRoszdravnadzor()
        ]);
    }

    /**
     * Загрузка файлов на сервер
     * @return mixed
     */
    public function actionFile()
    {
        $post = $_POST;
        $file = $_FILES['file'];
        $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
        $path = Yii::getAlias('@backend/uploads/') . $post['id'];

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        if (StmtAttachment::createAttch($post['id'], $file['name'], $file['type'], $path, $post))
            move_uploaded_file( $tempPath, $path.DIRECTORY_SEPARATOR.$file['name']);

        return $file;
    }

    /**
     * Сохранение файлов
     * @param $id
     * @return $this
     */
    public function actionSend($id)
    {
        $model = StmtAttachment::findOne($id);
        $file = $model->path.DIRECTORY_SEPARATOR.$model->file_name;
        return \Yii::$app->response->sendFile($file);
    }

    /**
     * Таблица 1.1
     * @param $range
     */
    public function actionSaveReport($range)
    {
        $date = json_decode($range);

        $params = [
            'StmtSearchArchive' => [
                'org' => Login::companyID(Yii::$app->user->id),
                'statement_date' => $date->startDate .' - '.$date->endDate
            ]
        ];

        return Stmt::saveReport($params, $date);
    }

    /**
     * Таблица 1.2
     * @param $range
     */
    public function actionReportPlaints($range)
    {
        $date = json_decode($range);

        $params = [
            'StmtSearchArchive' => [
                'org' => Login::companyID(Yii::$app->user->id),
                'statement_date' => $date->startDate .' - '.$date->endDate
            ]
        ];

        return Stmt::saveReportPlaints($date);
    }

    public function actionPagi()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $page = Yii::$app->getRequest()->get('page');
        $count = Yii::$app->getRequest()->get('count');

        switch (Login::getTypeUser(Yii::$app->user->id))
        {
            case 1:
//                $query = Stmt::find();
//                $query->where(['stmt.user_o' => Yii::$app->user->id]);
//                $query->andWhere(['NOT IN', 'status', [0]]);
//                $query->joinWith(['group', 'theme', 'send', 'call_first', 'stmt_status', 'deffered']);
//                $query->orderBy(['id' => SORT_DESC]);
//                $query->asArray();
//                $query->all();

                $query = Stmt::find()
                    ->where(['stmt.user_o' => Yii::$app->user->id])
                    ->andWhere(['NOT IN', 'status', [0]])
                    ->joinWith(['deffered'])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($count)
                    ->offset( ($page * $count) - $count)
                    ->asArray()
                    ->all();
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
                $query = Stmt::find()
                    ->where(['stmt.user_o' => Yii::$app->user->id])
                    ->andWhere(['NOT IN', 'status', [0]])
                    ->joinWith(['deffered'])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit($count)
                    ->offset( ($page * $count) - $count)
                    ->asArray()
                    ->all();
        }

//        $query = Stmt::find()
//        ->where(['stmt.company' => Login::companyID(Yii::$app->user->id)])
//        ->andWhere(['NOT IN', 'status', [0]])
//        ->joinWith(['deffered'])
//        ->orderBy(['id' => SORT_DESC])
//        ->limit($count)
//        ->offset( ($page * $count) - $count)
//        ->asArray()
//        ->all();

        $items = Stmt::find()
            ->where(['stmt.company' => Login::companyID(Yii::$app->user->id)])
            ->andWhere(['NOT IN', 'status', [0]])
            ->count();

        $data = array(
            'rows' => $query,
            'pagination' => [
              //  'count' => 10,
             //   'page' => 5,
                'pages' => 7,
                'size' => $items
            ]
        );

        return $data;
    }

    public function actionMonitoring($id)
    {
        $model = Monitoring::findOne($id);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'filename' => 'отчёт.pdf',
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $model->text,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
             //   'SetHeader'=>['Krajee Report Header'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Общий список подлежащих диспансеризации
     */
    public function actionSaveDialPeople()
    {
        $query = (new Query())
            ->from('peopleView')
            ->where(['smo' => Login::getSmoId(Yii::$app->user->id)])
            ->andWhere(['y' => 17])
            ->orderBy('PeopleID');

        $xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');

        foreach ($query->batch(100) as $items) {
            foreach ($items as $item)
            {
                $people = $xml_data->addChild('people');
                $people->addChild('ID', $item['id']);
                $people->addChild('ID_PAC', htmlspecialchars($item['id_pac']));
                $people->addChild('DISP', $item['disp']);
                $people->addChild('PD', $item['pd']);
                $people->addChild('FAM', $item['fam']);
                $people->addChild('IM', $item['im']);
                $people->addChild('OT', $item['ot']);
                $people->addChild('W', $item['w']);
                $people->addChild('DR', $item['dr']);
                $people->addChild('SNILS', $item['snils']);
                $people->addChild('VPOLIS', $item['vpolis']);
                $people->addChild('SPOLIS ', $item['spolis']);
                $people->addChild('NPOLIS', $item['npolis']);
                $people->addChild('CODE_MO', $item['code_mo']);
                $people->addChild('CONTACT', $item['contact']);
            }
        }
        $xml_data->addAttribute('total', $xml_data->count());

        $dom = dom_import_simplexml($xml_data)->ownerDocument;
        $dom->formatOutput = true;
        $path = Yii::getAlias('@backend/disp/');
        $name = 'people_'. Login::getSmoId(Yii::$app->user->id) .'.xml';
        $dom->save($path . $name);

        \Yii::$app->response->sendFile($path . $name)->send();
    }

    public function actionSaveDialMoPeople()
    {
        $mo = $_GET['mo'];
        $filters = DialParams::find()->where(['user' => Yii::$app->user->id])->one();

        $filter = unserialize($filters->params_mo);
        $params = unserialize($filters->params_people);

        $query = (new Query())
            ->from('peopleView')
            ->leftJoin('disp_file_action', 'disp_file_action.id_pac = peopleView.id_pac')
            ->where(['smo' => Login::getSmoId(Yii::$app->user->id), 'code_mo' => $mo])
            ->andWhere('LEN(contact) > 8 ')
            ->andFilterWhere([ 'in', 'y', $filter['y'] ])
            ->andFilterWhere([ 'in', 'pd', $params->pd ])
            ->andFilterWhere([ 'in', 'resultDial', $params->status ])
            ->andFilterWhere([ 'in', 'type', $params->action ])
            ->orderBy('peopleID');

//        $query = PeopleTest::find()
//            ->joinWith(['action', 'resultDial'], true, 'LEFT JOIN')
//            ->where(['smo' => Login::getSmoId(Yii::$app->user->id), 'code_mo' => $mo])
//            ->andWhere('LEN(contact) > 8 ')
//            ->andFilterWhere([ 'in', 'y', $filter['y'] ])
//            ->andFilterWhere([ 'in', 'pd', $params->pd ])
//            ->andFilterWhere([ 'in', 'resultDial', $params->status ])
//            ->andFilterWhere([ 'in', 'type', $params->action ])
//            ->orderBy('peopleID');

        $xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');

        foreach ($query->batch(100) as $items) {
            foreach ($items as $item)
            {
                $people = $xml_data->addChild('people');
                $people->addChild('ID', $item['peopleID']);
                $people->addChild('ID_PAC', htmlspecialchars($item['id_pac']));
                $people->addChild('DISP', $item['disp']);
                $people->addChild('PD', $item['pd']);
                $people->addChild('FAM', $item['fam']);
                $people->addChild('IM', $item['im']);
                $people->addChild('OT', $item['ot']);
                $people->addChild('W', $item['w']);
                $people->addChild('DR', $item['dr']);
                $people->addChild('SNILS', $item['snils']);
                $people->addChild('VPOLIS', $item['vpolis']);
                $people->addChild('SPOLIS ', $item['spolis']);
                $people->addChild('NPOLIS', $item['npolis']);
                $people->addChild('CODE_MO', $item['code_mo']);
                $people->addChild('CONTACT', $item['contact']);
            }
        }

        $xml_data->addAttribute('total', $xml_data->count());

        $dom = dom_import_simplexml($xml_data)->ownerDocument;
        $dom->formatOutput = true;
        $path = Yii::getAlias('@backend/disp/');
        $name = 'people_mo_'. Login::getSmoId(Yii::$app->user->id) .'.xml';
        $dom->save($path . $name);

        \Yii::$app->response->sendFile($path . $name)->send();
    }

    /**
     * Загрузка файлов диспансеризация
     * @return mixed
     */
    public function actionInteraction()
    {
        $file = $_FILES['file'];
        $model = new DispFile();

        if ($file) {
            $model->file = $file;
            $model->file_name = $file['name'];
            $model->file = UploadedFile::getInstanceByName('file');

            if ($model->upload()) {
//                $model->create();

                $model->openXML();
                return;
            }
        }
    }

    /**
     * Формирование отчёта за период
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionReportIntraservice($range)
    {
        $date = json_decode($range);

        $report = new ReportIntraservice();
        return $report->generateExcel($date);
    }
}
