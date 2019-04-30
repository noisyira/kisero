<?php

namespace frontend\controllers;

class SoapController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
           'index' => 'mongosoft\soapserver\Action',
        ];
    }

//    public function actionIndex()
//    {
//        return phpinfo();
//    }

    /**
     * @return string
     * @soap
     */
    public function getIndex($id)
    {
        return 'Index '. $id;
    }

    /**
     * @return array
     * @soap
     */
    public function getReportByDays()
    {
        $sql = 'select 
            dateadd(DAY,0, datediff(day,0, s.statement_date)) as day,
            count(case when company = 1 then \'\' end) as tfoms,
            count(case when company = 2 then \'\' end) as vtb,
            count(case when company = 1 then \'\' end) as ingos
            FROM stmt as s
                where user_o NOT IN (44, 47) and status NOT IN (0, 6)
                 group by dateadd(DAY,0, datediff(day,0, s.statement_date))';

        return Stmt::findBySql($sql)->all();
    }

    /**
     * @return array
     * @soap
     */
    public function getReportTotal()
    {
        $sql = 'SELECT  
              mgs.name as tip_name, 
              mgs.id as tip_id,
              COUNT(case when s.company = 1 then \'\' end) as tfoms, 
              COUNT(case when s.company = 2 then \'\' end) as vtb,
              COUNT(case when s.company = 3 then \'\' end) as ingos
            FROM call.dbo.stmt s
              LEFT OUTER JOIN dbo.mn_group_statement mgs ON s.tip_statement = mgs.id
              WHERE user_o NOT IN (44, 47) and status NOT IN (0, 6)
              GROUP BY  mgs.name,  mgs.id';

        return Stmt::findBySql($sql)->all();
    }
}
