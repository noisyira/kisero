<?php

namespace backend\controllers\user;

use app\models\DialCall;
use app\models\DialInterview;
use app\models\DialMoView;
use app\models\DialParams;
use app\models\DialResult;
use app\models\DialSetting;
use app\models\DispFiles;
use app\models\Monitoring;
use app\models\PeopleTest;
use app\models\PeopleView;
use app\models\SettingMoView;
use app\models\TestIndexSchemaBinding;
use backend\models\Cel;
use backend\models\Dial;
use backend\models\DialAnswers;
use backend\models\DialPeople;
use backend\models\DialQuestionList;
use backend\models\DispContent;
use backend\models\DispFilePeople;
use backend\models\Login;
use backend\models\MO;
use backend\models\People;
use backend\models\SipAccount;
use backend\models\StmtRoszdravnadzor;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RestStmtController implements the Rest controller for Stmt model.
 */
class DialStmtController extends Controller
{
    public $modelClass = 'backend\models\Stmt';
    public $client;
    public $ariAddress = 'ws://192.168.1.47:8088/ari/events?api_key=Operators:Cdvzc)s8kfVaAMn&app=dialer';
    public $sipEndpoints = array('rt', 'mypbx');

    protected function verbs()
    {
        return [
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
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if ($action->id == 'get-dial') {
            DialPeople::setStatus(Yii::$app->request->get('id'));
        }

        if ($action->id == 'get-people') {
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);

            DialPeople::setStatus($request->id, $request->mo);
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Список прикрепленных МО к оператору
     * @return array
     */
    public function actionIndex()
    {
        $setting = DialSetting::find()->where(['user' => Yii::$app->user->id])->asArray()->all();

        $mo = ArrayHelper::getColumn($setting, 'kodmo');
        $mo = implode(', ', array_filter($mo));
        $params = DialParams::find()->where(['user' => Yii::$app->user->id])->one();
        $param = unserialize($params->params_mo);

        if ($param['y']) {
            foreach ($param['y'] as $k => $v) {
                unset($param['y'][$k]);
                $param['y']['_' . $v] = true;
            }
        }

        $smo = Login::getSmoId(Yii::$app->user->id);
        $getY = ArrayHelper::getValue($this->getParamsMo(), 'y');
        $y = isset($getY) ? implode(",", $getY) : '';
        $connection = \Yii::$app->db;
        $model = $connection->createCommand("
            SELECT dfp.code_mo, mo.KODOKATO, mo.NAMMO, mo.ADRESMO, COUNT(*) AS total
             FROM  disp_file_people dfp
             LEFT JOIN rmp.dbo.mo mo ON mo.KODMO = dfp.code_mo
            WHERE  dfp.code_mo IN (" . $mo . ") AND dfp.code_smo = $smo and Right(year(dfp.file_date), 2) IN (" . $y . ")
            GROUP BY dfp.code_mo, mo.KODOKATO, mo.NAMMO, mo.ADRESMO
            ORDER BY dfp.code_mo
       ");
        $users = $model->queryAll();

        return array("MO" => $users, 'list' => $mo, 'params' => $param, 'mo' => $mo);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDialMo()
    {
        $request = Yii::$app->request->get();

        $params = json_decode($request['params']);

        $page = $params->page;
        $count = $params->count;
        $sortBy = $params->sortBy;
        $sortOrder = ($params->sortOrder == 'asc') ? 'SORT_ASC' : SORT_DESC;

        $filters = DialParams::find()->where(['user' => Yii::$app->user->id])->one();

        $filter = unserialize($filters->params_mo);

        if (DialSetting::checkMo(Yii::$app->user->id, $request['id'])) {
            $query = DispFilePeople::find();
            $query->joinWith(['actions', 'result']);
            $query->where(['code_mo' => $request['id']]);
            $query->andWhere(['code_smo' => Login::getSmoId(Yii::$app->user->id)]);

            $query->andFilterWhere(['in', 'pd', $this->getParamsPeople()->pd]);
            $query->andFilterWhere(['in', 'result', $this->getParamsPeople()->status]);
            $query->andFilterWhere(['in', 'action_type', $this->getParamsPeople()->action]);
            $query->andFilterWhere(['in', 'Right(year(file_date), 2)', $this->getParamsMo()['y']]);


//       if($this->getParamsPeople()->actionDV)
//       {
//           $query->andWhere([ 'disp' => 'ДВ1']);
//           $query->andWhere([ 'not in', 'action_type', ["2", "3"] ]);
//       }

            if ($this->getParamsPeople()->contact) {
                $query->andWhere('LEN(mobile) > 5');

            }

//       $query = PeopleView::find();
//       $query->joinWith(['action', 'resultDial']);
//       $query->where(['code_mo' => $request['id']]);
//       $query->andWhere(['smo' => Login::getSmoId(Yii::$app->user->id)]);
//
//       $query->andFilterWhere(['in', 'pd', $this->getParamsPeople()->pd]);
//       $query->andFilterWhere([ 'in', 'resultDial', $this->getParamsPeople()->status ]);
//       $query->andFilterWhere([ 'in', 'type', $this->getParamsPeople()->action ]);
//
//       if($this->getParamsPeople()->contact)
//       {
//           $query->andWhere('LEN(contact) > 5');
//       }
//
//       if($request['y'])
//       {
//           $query->andFilterWhere([ 'in', 'y', $request['y'] ]);
//       }

            $total = clone $query;
            $total->select(['disp_file_people.id']);
            $total->groupBy(['disp_file_people.id']);

            // $total->joinWith(['action', 'resultDial']);

            $query->offset(($page * $count) - $count);
            $query->orderBy([$sortBy => $sortOrder]);

            $data = array(
                'rows' => $query->limit($count)->asArray()->all(),
                'pagination' => [
                    'count' => $count,
                    //   'page' => 5,
                    'pages' => 7,
                    'size' => $total->count(), //  count($total->limit(false)->asArray()->all())
                ]
            );

            $MO = MO::getName($request['id']);

            Yii::$app->response->format = Response::FORMAT_JSON;

            return array('data' => $data, 'mo' => $MO, 'r' => $params->filters->pd, 'params' => $this->getParamsPeople(), 'filter' => $this->getParamsMo()['y']);
        }

        throw new NotFoundHttpException('400');
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetPeople()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        if (DialSetting::checkMo(Yii::$app->user->id, $request->mo)) {

            $data = DispFilePeople::find()
                ->with(['smo', 'mo', 'actions', 'answers', 'result'])
                ->where(['id' => $request->id])
                ->asArray()
                ->one();

            $questions = DialQuestionList::find()
                ->joinWith(['answers'])
                ->orderBy('form')
                ->asArray()
                ->all();

            $q = ArrayHelper::map($questions, 'id', 'text', 'form');
            $ans = ArrayHelper::map($questions, 'id', 'answers', 'form');

//            $data = DispContent::find()
//                ->with(['dispIdent', 'mo', 'answer', 'actions', 'dialPeople'])
//                ->where(['id' => $request->id])
//                ->asArray()
//                ->one();


//            $people = '';
//            $mo = $data['mo'];
//            $action = '';
            $zl_answer = ArrayHelper::map($data['answers'], 'question_key', 'answer_key');
            $result = $data['result'];
//            $description = $data['dialPeople']['description'];

            return array('answer' => $zl_answer, 'action' => $action, 'result' => $result, 'description' => $description, 'data' => $data, 'questions' => $q, 'answers' => $ans);
        }

        throw new NotFoundHttpException('400');
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function actionGetDial()
    {
        $request = Yii::$app->request->get();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        switch (Login::getCompanyUser(Yii::$app->user->id)->company) {
            case 1:
                $model = Dial::find()
                    ->where(['id' => $request['id']])
                    ->with(['people', 'answerlist'])
                    ->asArray()
                    ->one();

                $model['answerlist'] = ArrayHelper::map($model['answerlist'], 'question_key', function ($model) {
                    return ($model['question_key'] == '3.2') ? $model['other'] : $model['answer_key'];
                });
                break;
            default:
                $model = Dial::find()
                    ->where(['id' => $request['id']])
                    ->with(['people', 'answerlist'])
                    ->asArray()
                    ->one();

                $model['answerlist'] = ArrayHelper::map($model['answerlist'], 'question_key', function ($model) {
                    return ($model['question_key'] == '3.2') ? $model['other'] : $model['answer_key'];
                });
        }

        if (empty($model))
            throw new NotFoundHttpException('400');

        return $model;
    }

    /**
     * Сохранение исходящего вызова
     * @return bool|mixed
     */
    public function actionCallDial()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = new DialCall();
        $date = new \DateTime();

        $channel_id = $this->getChannelID();
        $new_record = $model->find()->where(['channel_id' => $channel_id])->one();

        if ($new_record)
            return false;

        $model->dial_people_id = $request->id;
        $model->phone = $request->phone;
        $model->user_src = SipAccount::getNumber(Yii::$app->user->id)->sip_private_identity;
        $model->datetime = Yii::$app->formatter->asDatetime($date);
        $model->channel_id = $channel_id;

        try {
            $model->save();
        } catch (\Exception $ex) {

        }

        $people = DialPeople::findOne($request->id);
        //  $people->user_o = 10;

        try {
            //     $people->update();
        } catch (\Exception $ex) {

        }

        return $this->getChannelID();
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

        $linkedid = Cel::find()->where(['uniqueid' => $channelEnd])->asArray()->one();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $linkedid['linkedid'];
    }

    /**
     */
    public function actionSaveDial()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $people = DialPeople::findOne(['pid' => $request->id]);
        $model = $people ? $people : new DialPeople();
        $model->pid = $request->id;
        $model->mo = $request->mo;
        $model->user_o = Yii::$app->user->id;
        $model->description = $request->model->description;
        $model->result = $request->model->result;

        $model->save();

        if ($request->model->question) {

            DialAnswers::deleteAll(['people_id' => $request->id]);
            foreach ($request->model->question as $key => $value) {

                $answer = new DialAnswers();
                $answer->question_key = $key;
                $answer->answer_key = $value;
                $answer->result = $request->model->result;

                $model->link('answer', $answer);
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $request;
    }

    /**
     * @return mixed
     */
    public function actionNotAnswerDial()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = DialPeople::findOne(['pid' => $request->id]);
        $model->user_o = Yii::$app->user->id;
        $model->result = 10;

        $model->save();

        return $model;
    }

    /**
     * @return mixed
     */
    public function actionReCallDial()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = DialPeople::findOne(['pid' => $request->id]);
        $model->user_o = Yii::$app->user->id;
        $model->description = $request->model->description ? $request->model->description : null;
        $model->result = 11;

        $model->save();

        return $model;
    }

    /**
     * @return mixed
     */
    public function actionNextCallDial()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $filters = DialParams::find()->where(['user' => Yii::$app->user->id])->one();

        $filter = unserialize($filters->params_mo);
        $params = unserialize($filters->params_people);
        $result = $params->status ? $params->status : [null];

        $query = DispFilePeople::find();
        $query->select('disp_file_people.id');
        $query->joinWith(['actions', 'result'], true, 'LEFT JOIN');
//        $query->joinWith(['actions']);
//        $query->with(['result']);
        $query->where(['code_mo' => $request->mo]);
        $query->andWhere(['code_smo' => Login::getSmoId(Yii::$app->user->id)]);
        $query->andWhere(['not in', 'disp_file_people.id', [$request->id]]);

        $query->andFilterWhere(['in', 'pd', $params->pd]);
        //   $query->andFilterWhere([ 'in', 'resultDial', $result ]);
        $query->andFilterWhere(['in', 'action_type', $params->action]);

        $query->orderBy('newid()');
        $query->limit(1);
        $query->asArray();

        if (empty($query->one()))
            throw new NotFoundHttpException('499');

        return $query->one();

        //        $query = PeopleView::find();
        //        $query->select('peopleID');
        //        $query->joinWith(['action', 'resultDial'], true, 'LEFT JOIN');
        //        $query->where(['code_mo' => $request->mo]);
        //        $query->andWhere(['smo' => Login::getSmoId(Yii::$app->user->id)]);
        //        $query->andWhere('LEN(contact) > 5 ');
        //
        //        $query->andFilterWhere([ 'in', 'pd', $params->pd ]);
        //        $query->andFilterWhere([ 'in', 'resultDial', $result ]);
        //        $query->andFilterWhere([ 'in', 'type', $params->action ]);
        //        $query->andFilterWhere([ 'in', 'y', $request->y]);
        //
        //        $query->orderBy('fam');
        //        $query->limit(1);
        //        $query->asArray();
        //
        //        return $query->one();
    }

    /**
     * @return int|string
     */
    public function actionReportUser()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $subTotal = "(SELECT Count(*) FROM " . DialPeople::tableName() . " dp WHERE dp.user_o = " . Login::tableName() . ".id) as total";
        $subSc = "(SELECT Count(*) FROM " . DialPeople::tableName() . " dp WHERE dp.user_o = " . Login::tableName() . ".id AND dp.result BETWEEN 1 AND 9) as success";
        $subRc = "(SELECT Count(*) FROM " . DialPeople::tableName() . " dp WHERE dp.user_o = " . Login::tableName() . ".id AND dp.result = 11) as recall";
        $subNc = "(SELECT Count(*) FROM " . DialPeople::tableName() . " dp WHERE dp.user_o = " . Login::tableName() . ".id AND dp.result = 10) as notcall";
        $subPr = "(SELECT Count(*) FROM " . DialPeople::tableName() . " dp WHERE dp.user_o = " . Login::tableName() . ".id AND dp.result = 12) as process";

        $model = DialPeople::find()
            ->select(['user_o,
             COUNT(dial_people.id) AS cnt
             '])
            ->joinWith(['operator'])
            ->where(['company' => Login::getCompanyUser(Yii::$app->user->id)->company])
            ->groupBy(['user_o'])
            ->asArray()
            ->all();

        $model = Login::find()
            ->select(["*",
                $subTotal, $subSc, $subRc, $subNc, $subPr
            ])
            ->where(['company' => Login::getCompanyUser(Yii::$app->user->id)->company])
            ->asArray()
            ->all();

        return $model;
    }


    /**
     * Список мед. организаций
     * @return array
     */
    public function actionGetListMo()
    {
        $query = DispContent::find()
            ->select(['code_mo'])
            ->groupBy(['code_mo'])
            ->asArray()
            ->all();

        $mainQuery = MO::find();
        $mainQuery->with('okato');
        $mainQuery->where(['IN', 'KODMO', ArrayHelper::map($query, 'code_mo', 'code_mo')]);
        $mainQuery->orderBy('KODMO');

        $data = ArrayHelper::index($mainQuery->asArray()->all(), 'NAMMO', function ($data) {
            return $data['okato']['NAME_RAY'];
        });

        $selected = ArrayHelper::map(DialSetting::find()->where(['user' => Yii::$app->user->id])->asArray()->all(), 'kodmo', function ($data) {
            return true;
        });

        return array('data' => $data, 'selected' => $selected);
    }

    /**
     * Сохранение настроек
     * @return bool
     */
    public function actionSettingMo()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        foreach ($request->data as $k => $v) {
            if ($v == true) {
                if (!DialSetting::find()->where(['user' => Yii::$app->user->id, 'kodmo' => $k])->one()) {
                    DialSetting::saveMo(Yii::$app->user->id, $k);
                }
            } else {
                DialSetting::removeMo(Yii::$app->user->id, $k);
            }
        }

        return $request->data;
    }

    /**
     * Сохранить параметры МО
     * @return bool
     */
    public function actionSaveParamsMo()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = array();

        foreach ($request->data->y as $k => $v) {
            if ($v)
                $params['y'][] = str_replace("_", "", $k);
        }
        DialParams::saveParamsMO($params);

        return true;
    }

    public function getParamsMo()
    {
        $data = DialParams::findOne(['user' => Yii::$app->user->id]);

        $params = unserialize($data->params_mo);

        return $params;
    }

    /**
     * Сохранить параметры People
     * @return bool
     */
    public function actionSaveParamsPeople()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        DialParams::setParamsPeople($request->data);

        return $request->data;
    }

    /**
     * @return mixed
     */
    public function getParamsPeople()
    {
        $data = DialParams::findOne(['user' => Yii::$app->user->id]);

        $params = unserialize($data->params_people);

        return $params;
    }

    public function actionGetMonitoring()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return array('data' => Monitoring::find()->orderBy(['range' => SORT_ASC])->asArray()->all());
    }

    public function actionGetReport()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $userList = Login::find()->select('id')->where(['company' => Login::companyID(Yii::$app->user->id)])->asArray()->all();

        $users = implode(',', ArrayHelper::getColumn($userList, 'id'));

        $query = DialResult::find();
        $query->select(["dial_result.id", "dial_result.name"]);
        $query->joinWith('people');
        $query->addSelect(["COUNT(CASE 
             WHEN dial_people.user_o IN (" . $users . ") AND dial_people.dt BETWEEN '" . date('Y-m-d', strtotime($request->start)) . "' AND '" . date('Y-m-d', strtotime($request->end)) . "'
             THEN 1 ELSE NULL END) as total"]);
        $query->where(['IN', 'dial_result.id', [1, 2, 3, 4, 5, 10, 11]]);
        $query->groupBy(['dial_result.id', 'dial_result.name']);

        $data = $query->asArray()->all();

        $res = function ($data) {
            $sum = 0;
            foreach($data as $elem)
            {
                $sum += $elem['total'];
            }
            return $sum;
        };

        return array('data' => $query->asArray()->all(), 'req' => $request, 'calls' => $res($data));
    }


    public function actionRoszdravnadzor()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = new StmtRoszdravnadzor();
        $model->theme = (int)$request->theme;

        return $model->save();
    }
}
