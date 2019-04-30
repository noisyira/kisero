<?php

namespace backend\controllers\user;

use backend\models\Login;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\debug\models\search\Log;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ArchiveStmtController implements the Rest controller for Stmt model.
 */
class ArchiveStmtController extends Controller
{

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
        ];
    }

    /**
     * Список прикрепленных МО к оператору
     * @return array
     */
    public function actionIndex()
    {
        $c = Login::companyID(Yii::$app->getUser()->id);
        $url = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'voiceMessages' . DIRECTORY_SEPARATOR . $c;
        $data = $this->dirToArray($url, $c);
        krsort($data);
        array_splice($data, 10);

        return  $data;
    }

    private function dirToArray($dir, $company)
    {
        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value, $company);
                }
                else
                {
                    $result[] = DIRECTORY_SEPARATOR . 'voiceMessages' . DIRECTORY_SEPARATOR . $company . DIRECTORY_SEPARATOR . basename($dir) . DIRECTORY_SEPARATOR . $value;
                }
            }
        }

        return $result;
    }
}
