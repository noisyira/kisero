<?php


namespace backend\models;


use Yii;
use yii\helpers\VarDumper;

class ReportIntraservice
{
    public function generateExcel($range)
    {
        VarDumper::dump($range, 100, true);

        $data = Stmt::find()
            ->joinWith(['deffered', 'operator'])
            ->andWhere(['between', 'statement_date', $range->startDate, $range->endDate ])
            ->andWhere(['stmt.company' => 2])
            ->andWhere(['status' => 3])
            ->asArray()
            ->all()
        ;
        $objPHPExcel = new \PHPExcel();
        $sheet = 0;
        $objPHPExcel->setActiveSheetIndex($sheet);

        //Ориентация страницы и  размер листа
        $objPHPExcel->getActiveSheet()->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        //Поля документа
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setTop(0.5);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setLeft(0.75);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setBottom(0.5);

        $objPHPExcel->getActiveSheet()->setTitle('Интрасервис');

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Наименование');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'e-mail регистратора');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'e-mail исполнителя');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Линия принятия обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Линия принятия обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Статус');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Содержание обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Дата и время поступления обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Дата окончания срока рассмотрения обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Регион обратившегося');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Муниципальное образование');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Источник поступления');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'Способ обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Вид обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Тема обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'Примечание к теме обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Жалоба');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', 'Необходимость экспертизы');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', '№ входящего документа');
        $objPHPExcel->getActiveSheet()->setCellValue('T1', '№ исходящего документа');
        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'Дата уведомления о принятии в работу');
        $objPHPExcel->getActiveSheet()->setCellValue('V1', 'Дата промежуточного ответа');
        $objPHPExcel->getActiveSheet()->setCellValue('W1', 'Дата окончательного ответа');
        $objPHPExcel->getActiveSheet()->setCellValue('X1', 'Наименование организации поступления');
        $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Номер телефона Заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Фамилия Заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AA1', 'Имя Заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AB1', 'Отчество Заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AC1', 'Дата рождения Заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AD1', 'ЕНП Заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AE1', 'Страховая принадлежность заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AF1', 'Адрес электронной почты заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AG1', 'Адрес для обратного ответа заявителя');
        $objPHPExcel->getActiveSheet()->setCellValue('AH1', 'Фамилия лица, в отношении которого поступило обращение');
        $objPHPExcel->getActiveSheet()->setCellValue('AI1', 'Имя лица, в отношении которого поступило обращение');
        $objPHPExcel->getActiveSheet()->setCellValue('AJ1', 'Отчество лица, в отношении которого поступило обращение');
        $objPHPExcel->getActiveSheet()->setCellValue('AK1', 'Дата рождения лица, в отношении которого поступило обращение');
        $objPHPExcel->getActiveSheet()->setCellValue('AL1', 'ЕНП лица, в отношении которого поступило обращение');
        $objPHPExcel->getActiveSheet()->setCellValue('AM1', 'Страховая принадлежность лица, в отношении которого поступило обращение');
        $objPHPExcel->getActiveSheet()->setCellValue('AN1', 'Результат обращения');
        $objPHPExcel->getActiveSheet()->setCellValue('AO1', 'Ответ для обратившегося');
        $objPHPExcel->getActiveSheet()->setCellValue('AP1', 'Канал передачи ответа обратившемуся');

        $row = 2;

        foreach ($data as $item)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, trim($item['deffered']['fam']).' '.trim($item['deffered']['im']).' '.trim($item['deffered']['ot']));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, ' ');
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, ' ');
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'СП1');
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'СП2');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'выполнено');
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $item['theme_statement_description']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $item['statement_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $item['statement_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, 'Ставропольский край');
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, 'напрямую от заявителя');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, '1. По телефону «горячей линии»');
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, 'Консультация');
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, $item['theme_statement']);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, 'Экспертиза не требуется');
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, $item['statement_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, $item['deffered']['phone']);
            $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, trim($item['deffered']['fam']));
            $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, trim($item['deffered']['im']));
            $objPHPExcel->getActiveSheet()->setCellValue('AB'.$row, trim($item['deffered']['ot']));
            $objPHPExcel->getActiveSheet()->setCellValue('AC'.$row, $item['deffered']['dt']);
            $objPHPExcel->getActiveSheet()->setCellValue('AD'.$row, $item['deffered']['id_erz']);
            $objPHPExcel->getActiveSheet()->setCellValue('AE'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AF'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AG'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AH'.$row, $item['deffered']['add_fio']);
            $objPHPExcel->getActiveSheet()->setCellValue('AI'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AK'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AL'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AM'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AN'.$row, 'дана консультация');
            $objPHPExcel->getActiveSheet()->setCellValue('AO'.$row, '');
            $objPHPExcel->getActiveSheet()->setCellValue('AP'.$row, 'Звонок обратившемуся');

            $row++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:AP1')->getAlignment()->setWrapText(true)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);


        $filename = "Отчет_Интрасервис.xls";
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_clean();
        Yii::$app->end();
    }


}