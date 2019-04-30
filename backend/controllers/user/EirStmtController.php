<?php
namespace backend\controllers\user;

use backend\models\EirPeople;
use backend\models\EirPeopleCall;
use backend\models\EirPeopleView;
use backend\models\EirSetting;
use backend\models\Login;
use backend\models\MO;
use backend\models\Pacient;
use backend\models\PacientKISERO;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use Zend\Stdlib\DateTime;

/**
 * RestStmtController implements the Rest controller for Stmt model.
 */
class EirStmtController extends Controller
{
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
          //  'get-mo-list' => ['GET', 'HEAD'],
        ];
    }

    public function actionEirGet()
    {
        $eir = EirSetting::findOne(['user_id' => Yii::$app->user->id]);
        $ready = isset($eir) ? true : false;
        $countTotal = 0;
        $countPhone = 0;
        $countCall = 0;

        if ($eir) {
            $people = EirPeople::find()->select('nnapr')->asArray()->all();
            $ids = ArrayHelper::getColumn($people, 'nnapr');
            $phone = clone $this->queryEir($eir);
            $phone->andWhere(['IS NOT', 'TEL', null]);

            $call = clone $this->queryEir($eir);
            $call->andWhere(['IN', 'NNAPR', $ids]);
            $tot = $this->queryEir($eir);

            $countTotal = $tot->count();
            $countPhone = $phone->count();
            $countCall = $call->count();
        }

        $code_mo = MO::find()->where(['IN', 'KODMO', unserialize($eir->code_mo) ])->asArray()->all();
        $params = $eir->params;
        $range = $eir->range;

        return array('code_mo' => $code_mo,
            'params' => $params,
            'range' => $range,
            'ready' => $ready,
            'total' => $countTotal,
            'phone' => $countPhone,
            'call' => $countCall,
            'eir' => $eir,
            'moList' => unserialize($eir->code_mo)
        );
    }

    private function queryEir($params)
    {
        $model = Pacient::find();
        $moList = unserialize($params->code_mo);

        $model->where(['IN', 'MO', $moList]);
        $model->andWhere(['SMO' => Login::getSmoId(Yii::$app->user->id)]);
        $model->andWhere(['between', $params->params, $params->from_date, $params->to_date]);

        if ($params->params == 'DPGOSP')
        {
            $model->andWhere(['DNGOSP' => null]);
            $model->andWhere(['DANUL' => null]);
        }

        if ($params->params == 'DANUL')
        {
            $model->andWhere(['PANUL' => 1]);
        }

        return $model;
    }

    /**
     * Список МО
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function actionGetMoList()
    {
//        $request = Yii::$app->request->get();
//
//        $model = MO::find()
//            ->orderBy('KODMO')
//            ->asArray()
//            ->all();
//
//        if (empty($model))
//            throw new NotFoundHttpException('400');
//
//        // return array('r' => $model);
//        return $model;
    }

    public function actionGetMoAsync()
    {
        $request = Yii::$app->request->get();

        if (!empty($request['data']))
            $model = MO::find()
                ->where(['LIKE', 'KODMO', $request['data']])
                ->orWhere(['LIKE', 'NAMMO', $request['data']])
                ->orderBy('KODMO')
                ->asArray()
                ->all();

        if (empty($model))
            throw new NotFoundHttpException('400');

        // return array('r' => $model);
        return $model;
    }

    public function actionEirSettingSave()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $today = new \DateTime();
        $t = $today->format('m.d.Y');

        $today = new \DateTime();
        $second = $today->sub(new \DateInterval('P' . $request->range . 'D'));
        $s = $second->format('m.d.Y');

        $today = new \DateTime();
        $future = $today->add(new \DateInterval('P' . $request->range . 'D'));
        $f = $future->format('m.d.Y');

        $eir = (EirSetting::findOne(['user_id' => Yii::$app->user->id])) ? EirSetting::findOne(['user_id' => Yii::$app->user->id]) : new EirSetting();
        $eir->user_id = Yii::$app->user->id;
        $eir->code_mo = serialize($request->mo);
        $eir->params = $request->param;
        $eir->range = $request->range;

        switch ($request->param)
        {
            case 'DANUL':
                $eir->to_date = $t;
                $eir->from_date = $s;
                break;
            case 'DPGOSP':
                $eir->to_date = $f;
                $eir->from_date = $t;
                break;
            case 'DPOGOSP':
                $eir->to_date = $f;
                $eir->from_date = $t;
                break;
            default:
                $eir->to_date = null;
                $eir->from_date = null;
                break;
        }

        $eir->save();
        return array('mo' => $request->mo, 'param' => $request->param, 'range' => $request->range);
    }

    /* EIR INTERVIEW PEOPLE */

    public function actionEirGetPeople()
    {
       // $request = Yii::$app->request->get();
        $setting = EirSetting::find()->where(['user_id'=> Yii::$app->user->id])->one();

        $peopleID = $this->getPeopleList($setting);

        $pacient = PacientKISERO::find();
        $pacient->where(['IDZAP' => $peopleID->IDZAP]);
        $pacient->asArray();

        return array('data' => $pacient->one(), 'setting' => $setting);
    }

    private function getPeopleList($setting)
    {
        if(!$setting)
            return false;

        $peoples = EirPeople::find()->select('nnapr')->asArray()->all();
        $ids = ArrayHelper::getColumn($peoples, 'nnapr');

        $people = $this->queryEir($setting);
        $people->select(['IDZAP']);
        $people->andWhere(['NOT', ['TEL' => null]]);
        $people->andWhere(['NOT IN', 'NNAPR', $ids]);

       return $people->one();
    }


    public function actionEirSavePeople()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = new EirPeople();
        $model->params = $request->params->params;
        $model->nnapr = $request->pacient->NNAPR;
        $model->result = 1;
        $model->user_id = $request->params->user_id;
        $model->panul = isset($request->value->panul)?$request->value->panul:null;
        $model->comment = isset($request->value->comment)?$request->value->comment:null;
        $model->another_phone = !empty($request->value->another_phone)?1:null;

        $model->save();

        if($request->params->params == 'DPOGOSP')
        {
            foreach ($request->value as $item=>$v)
            {
                $question = new EirPeopleCall();
                $question->question = $item;
                $question->answer = $v;

                $model->link('answer', $question);
            }
        }
        return  $request;

    }

    /* EIR REPORT */
    public function actionEirReport()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $model = EirPeopleView::find();
        $model->select([
            "user_id", "fam", "im", "ot", "name", "code_smo", "subdivision", "role",
            "COUNT(nnapr) AS total",
            "COUNT(CASE WHEN params = 'DPGOSP' THEN 1 ELSE null END) AS 'DPGOSP'",
            "COUNT(CASE WHEN params = 'DPOGOSP' THEN 1 ELSE null END) AS 'DPOGOSP'",
            "COUNT(CASE WHEN params = 'DANUL' THEN 1 ELSE null END) AS 'DANUL'"
        ]);
        $model->where(['code_smo' => Login::getSmoId(Yii::$app->user->id)]);
        $model->groupBy(['user_id', 'fam', 'im', 'ot', 'name', 'code_smo', 'subdivision', 'role']);

        if( $request->data )
            $model->andWhere(['BETWEEN', 'insdate', $request->data->fromDate, $request->data->toDate]);

        return $model->asArray()->all();
    }

}