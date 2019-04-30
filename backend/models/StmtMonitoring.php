<?php

namespace backend\models;

use app\models\StmtRelate;
use backend\components\Helpers;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "stmt".
 *
 * @property integer $id
 * @property integer $statement
 * @property string $statement_date
 * @property integer $tip_statement
 * @property integer $form_statement
 * @property integer $plaint
 * @property integer $stage_statement
 * @property string $theme_statement
 * @property string $theme_statement_description
 * @property string $expired
 * @property integer $company
 * @property integer $user_o
 * @property string $status
 * @property string $res_msg
 * @property string $cash_back
 */
class StmtMonitoring extends Stmt
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getQuery()
    {
        $dt = Yii::$app->request->post('Monitoring')['range']?Yii::$app->request->post('Monitoring')['range']:Yii::$app->request->post('range');
        $range = StmtMonitoring::dateRange($dt);
        $query = StmtMonitoring::find();
        if ($range) {
            $query->where(['between', 'statement_date', Yii::$app->formatter->asDate($range[0]), Yii::$app->formatter->asDate($range[1])]);
        } else {
            $query->where('YEAR(statement_date) = ' . date("Y"));
        }
        $query->andWhere(['status' => 3]);
        return $query;
    }

    public static function getTotal($tip_statement = null, $company = null)
    {
        $query = self::getQuery();

        if($tip_statement)
            $query->andWhere(['tip_statement' => $tip_statement]);

        if($company == 1)
        {
            $query->andWhere(['company' => 1]);
        } else if ($company === 0)
        {
            $query->andWhere(['IN', 'company', [2, 3]]);
        } else
        {

        }

        return $query->count();
    }


    public static function getVoiceStmt($form_statement = null, $statement = null)
    {
        $query = self::getQuery();

        if(isset($form_statement))
            $query->andWhere(['form_statement' => $form_statement]);

        if(isset($statement))
            $query->andWhere(['statement' => $statement]);

        return $query->count();
    }

    public static function getCauseStmt($theme_statement, $tip_statement)
    {
        $query = self::getQuery();
        $query->andWhere(['theme_statement' => $theme_statement, 'tip_statement' => $tip_statement]);

        return $query->count();
    }

    public static function precent($a, $b)
    {
        if($b == 0)
            return number_format(0, 2, '.', '');

         return number_format($a/$b*100, 2, '.', '');
    }

    public static function getPlaint($theme_statement = null)
    {
        $query = self::getQuery();
        $query->andWhere(['plaint' => 1]);

        if(isset($theme_statement))
            $query->andWhere(['IN', 'theme_statement', $theme_statement]);

        return $query->count();
    }

    public function dateRange($date)
    {
        return explode(' - ', $date);
    }
}
