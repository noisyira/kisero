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
class StmtAdmin extends Stmt
{
    /**
     * @param $key
     * @param $c
     * @param $range
     * @param bool $form
     * @param bool $plaint
     * @return int|string
     */
    public static function getValuePlaint($key, $c, $range, $form = null, $plaint = null)
    {
        $company =  Login::getCompanyUser(Yii::$app->user->id)->company;

        $dt =  explode(' - ', $range);

        $data = Stmt::find();
        $data->where(['status' => 3, 'tip_statement' => 1]);
        $data->andWhere(['between', 'statement_date', Yii::$app->formatter->asDate($dt[0]), Yii::$app->formatter->asDate($dt[1]) ]);
        $data->andWhere([ 'theme_statement' => $key ]);

        if(isset($form))
            $data->andWhere([ 'form_statement' => $form ]);

        if(isset($plaint))
            $data->andWhere([ 'plaint' => $plaint ]);

        if(in_array(1, $c))
        {
            $data->andWhere(['company' => 1]);

        } else {
            $data->andWhere(['IN', 'company',  [2,3]]);
        }

        return $data->count();
    }

    public static function getTotalAll($statement = null, $c, $range, $form, $cell)
    {
        if(in_array($cell, Helpers::stopList()))
            return 'x';

        $company =  Login::getCompanyUser(Yii::$app->user->id)->company;
        $dt =  explode(' - ', $range);

        $data = Stmt::find();
        $data->joinWith(['close', 'theme']);
//        $data->where(['tip_statement' => 1]);
        $data->andWhere(['NOT IN', 'status', [0, 9]]);
        // $data->andWhere(['NOT IN', 'user_o', [44, 47]]);
//        $data->andWhere(['between', 'statement_date',  $range->startDate, $range->endDate]);
        $data->andWhere(['between', 'statement_date', Yii::$app->formatter->asDate($dt[0]), Yii::$app->formatter->asDate($dt[1]) ]);

        if(isset($statement))
            $data->andWhere(['IN', 'statement', $statement]);

        if(isset($form))
            $data->andWhere([ 'form_statement' => $form ]);

        if(in_array(1, $c))
        {
            $data->andWhere(['company' => 1]);

        } else {
            $data->andWhere(['IN', 'company',  [2,3]]);
        }

        return count($data->asArray()->all());
    }

    public static function getPlaintlAll($c, $range, $form, $cell)
    {
        if(in_array($cell, Helpers::stopList()))
            return 'x';

        $company =  Login::getCompanyUser(Yii::$app->user->id)->company;
        $dt =  explode(' - ', $range);

        $data = Stmt::find();
        $data->joinWith(['close', 'theme']);
        $data->where(['tip_statement' => 1]);
        $data->andWhere(['NOT IN', 'status', [0, 9]]);
        // $data->andWhere(['NOT IN', 'user_o', [44, 47]]);
//        $data->andWhere(['between', 'statement_date',  $range->startDate, $range->endDate]);
        $data->andWhere(['between', 'statement_date', Yii::$app->formatter->asDate($dt[0]), Yii::$app->formatter->asDate($dt[1]) ]);

        if(isset($form))
            $data->andWhere([ 'form_statement' => $form ]);

        if(in_array(1, $c))
        {
            $data->andWhere(['company' => 1]);

        } else {
            $data->andWhere(['IN', 'company',  [2,3]]);
        }

        return count($data->asArray()->all());
    }

    public static function getValueAll($key, $c, $range, $form, $cell)
    {
        if(in_array($cell, Helpers::stopList()))
            return 'x';

        $company =  Login::getCompanyUser(Yii::$app->user->id)->company;
        $dt =  explode(' - ', $range);

        $data = Stmt::find();
        $data->joinWith(['close', 'theme']);
//        $data->where(['tip_statement' => 1]);
        $data->andWhere(['NOT IN', 'status', [0, 9]]);
        // $data->andWhere(['NOT IN', 'user_o', [44, 47]]);
//        $data->andWhere(['between', 'statement_date',  $range->startDate, $range->endDate]);
        $data->andWhere(['between', 'statement_date', Yii::$app->formatter->asDate($dt[0]), Yii::$app->formatter->asDate($dt[1]) ]);
        $data->andWhere([ 'k' => $key ]);

        if(isset($form))
            $data->andWhere([ 'form_statement' => $form ]);

        if(in_array(1, $c))
        {
            $data->andWhere(['company' => 1]);

        } else {
            $data->andWhere(['IN', 'company',  [2,3]]);
        }

        return count($data->asArray()->all());
    }

}
