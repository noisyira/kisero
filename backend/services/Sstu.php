<?php
/**
 * Created by PhpStorm.
 * User: Уракин_ДВ
 * Date: 13.02.2019
 * Time: 14:46
 */
namespace backend\services;

use backend\models\StmtAction;
use backend\models\StmtAttachment;
use backend\models\StmtDeffered;

use backend\models\StmtSstu;
use yii\base\Exception;
use yii\httpclient\Client;

class Sstu
{
    private $ListStmt;
    private $Path;

    public function __construct($list)
    {
        $this->ListStmt = $list;
        $this->Path = \Yii::getAlias('@backend/disp/') . "sstu.zip";
    }

    public  function SstuArchive()
    {
        $this->Start();

        $this->SaveAsFile();

        $this->Clear();
    }

    public function SendSstu()
    {
        $this->Start();

        $this->Send();

        $this->Clear();
    }

    public function CreateJson($stmt_id)
    {
        $smtm = \backend\models\Stmt::findOne($stmt_id);
        $people = StmtDeffered::find()->where(['stmt_id' => $stmt_id])->one();
        $files = StmtAttachment::find()->where(['stmt_id' => $stmt_id])->orderBy(['id' => SORT_DESC])->one();
        $close = StmtAction::find()->where(['stmt_id' => $stmt_id])->andWhere(['action' => 4])->one();

        $fam = $people->fam . " " . $people->im . " " . $people->ot;
        $str= file_get_contents($files->path . DIRECTORY_SEPARATOR . $files->file_name);

        $createDate = $smtm->date_send ? \Yii::$app->formatter->asDate($smtm->date_send, 'php:Y-m-d') : \Yii::$app->formatter->asDate($smtm->statement_date, 'php:Y-m-d');

        $data = array(
            "departmentId" => "fc4905c9-1dd0-4e87-b214-6b0d44a69ecb",
            "isDirect" => true,
            "format" => "Other",
            "number" => $files->n_attach != "undefined" ? $files->n_attach : $files->stmt_id,
            "createDate" => \Yii::$app->formatter->asDate($smtm->statement_date, 'php:Y-m-d'),
            "name" => $fam,
            "address" => $people->name_okato,
            "email" => "",
            "questions" => array(array(
                "code" => SstuTheme::Get($smtm->theme_statement),
                "status" => "Answered",
                "incomingDate" => $createDate,
                "registrationDate" => \Yii::$app->formatter->asDate($smtm->statement_date, 'php:Y-m-d'),
                "responseDate" => $createDate,
                "attachment" => array(
                    "name" => (!empty($files->file_name))?$files->file_name:"Тестовый файл.pdf",
                    "content" => chunk_split(base64_encode($str))
                )
            ))
        );

        $file = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $file;
    }

    public function Send(){

        $client = new Client(['baseUrl' => 'https://xn--q1aade.xn--p1ai/']);

        $loginResponse = $client->post('/', [
            'Login' => 'RGO26-KhrapachMV',
            'Password' => 'ZL94k?N2B8',
        ],
            [
                'timeout' => 120
            ]
        )->send();

        $loginResponse->cookies->removeAll();
        $loginResponse->cookies->get('PHPSESSID');

        $client->post('HandlingReportImport')
            ->setCookies($loginResponse->cookies)
            ->addFile('file', $path = \Yii::getAlias('@backend/disp/') . "sstu.zip")
            ->send();
    }

    public function Clear()
    {
        unlink($this->Path);
    }

    public function Start()
    {
        $main = [];

        foreach ($this->ListStmt as $stmt)
        {
            $main[$stmt] = $this->CreateJson($stmt);
            StmtSstu::SaveSstu($stmt);
        }

        $this->CreateZip($main);
    }

    public function CreateZip($files)
    {
        $zip = new \ZipArchive();

        if($zip->open($this->Path, \ZipArchive::CREATE) !== TRUE)
        {
            throw new Exception('Cannot create a zip file');
        }

        foreach ($files as $id => $file) {
            $zip->addFromString("sstu_{$id}.json", $file);
        }
        $zip->close();
    }

    public function SaveAsFile()
    {
        \Yii::$app->response->sendFile($this->Path)->send();
    }
}