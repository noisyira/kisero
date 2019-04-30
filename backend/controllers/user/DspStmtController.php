<?php

namespace backend\controllers\user;

use backend\models\Login;
use Yii;
use yii\rest\Controller;


/**
 * DspStmtController implements the Rest controller for Stmt model.
 */
class DspStmtController extends Controller
{

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'list' => ['POST', 'HEAD'],
            'people' => ['GET', 'HEAD'],
        ];
    }

    public function actionIndex()
    {
        $result = \Yii::$app->db->createCommand("exec dbo.getDspMoList @smo = :smo")
            ->bindValue(':smo' , Login::getSmoId(Yii::$app->user->id))
            ->queryAll();

        return $result;
    }


    public function actionList()
    {
        $request = Yii::$app->request->get();
        $params = json_decode($request['params']);

        $page = $params->page;
        $pagesize = $params->count;
        $sortBy = $params->sortBy;
        $sortOrder = $params->sortOrder;

        $moInfo = \Yii::$app->db->createCommand("exec dbo.getMO @mo = :mo")
            ->bindValue(':mo', '260003')
            ->queryOne();

        $list = \Yii::$app->db->createCommand("exec dbo.getDspPeopleList 
            @smo = :smo, 
            @mo = :mo, 
            @pagenum = :pagenum, 
            @pagesize = :pagesize, 
            @SortOrder = :SortOrder, 
            @SortColumn = :SortColumn
        ")
            ->bindValue(':smo' , Login::getSmoId(Yii::$app->user->id))
            ->bindValue(':mo',  '260003')
            ->bindValue(':pagenum', $page)
            ->bindValue(':pagesize', $pagesize)
            ->bindValue(':SortColumn', $sortBy)
            ->bindValue(':SortOrder', $sortOrder)
            ->queryAll();

        $count = \Yii::$app->db->createCommand("exec dbo.getDspPeopleListCount @mo = :mo, @smo = :smo")
            ->bindValue(':smo' , Login::getSmoId(Yii::$app->user->id))
            ->bindValue(':mo',  '260003')
            ->queryScalar();

        $data = array(
            'rows' => $list,
            'pagination' => [
                'count' => $pagesize,
               //    'page' => 5,
                'pages' => 7,
                'size' => $count, //  count($total->limit(false)->asArray()->all())
            ]
        );

        return array('data' => $data, 'ss' => $params, 'mo' => $moInfo, 'count' => $count);
    }

    public function actionPeople()
    {
        $request = Yii::$app->request->get();

        $result = \Yii::$app->db->createCommand("exec dbo.getDspPeopleInfo @smo = :smo, @mo = :mo, @pid = :pid")
            ->bindValue(':smo' , Login::getSmoId(Yii::$app->user->id))
            ->bindValue(':mo' ,$request['mo'])
            ->bindValue(':pid' ,$request['pid'])
            ->queryOne();

        return array('data' => $result, 'p' => $request['mo']);
    }
}
