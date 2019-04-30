<?php
/**
 * Created by PhpStorm.
 * User: Уракин_ДВ
 * Date: 25.04.2016
 * Time: 14:28
 */

namespace backend\components;


class Helpers
{
    /**
     * @param $array
     * @return mixed
     */
    public static function formatDate($array)
    {
        if($array['statement_date'])
            $array['statement_date'] = date('d-m-Y H:i:s', strtotime($array['statement_date']));

        if($array['expired'])
            $array['expired'] = date('d-m-Y', strtotime($array['expired']));

//        if($array['deffered']['dt'])
//            $array['deffered']['dt'] = date('d-m-Y', strtotime($array['deffered']['dt']));

        if($array['action']['action_date'])
            $array['action']['action_date'] = date('d-m-Y', strtotime($array['action']['action_date']));

        return $array;
    }

    public static function getStatus($id)
    {
        switch ($id):
            case 0:
                echo "i равно 0";
                break;
            case 1:
                echo "i равно 1";
                break;
            case 2:
                echo "i равно 2";
                break;
            default:
                echo "i не равно to 0, 1 или 2";
        endswitch;
    }

    public static function routeOperatorFirst()
    {
        return [
            '/' => [
                'view' => 'index',
                'js' => 'js/index.js',
                'injection' => ['Stmt',],
            ],
            '/create' => [
                'view' => 'first/create',
                'js' => 'first/js/create.js',
                'injection' => ['Stmt',],
            ],
            '/dial' => [
                'view' => 'dial',
                'js' => 'js/dial.js',
                'injection' => ['DialPeople'],
            ],
            '/archiveMessages' => [
                'view' => 'archive/messages',
                'js' => 'archive/js/messages.js',
                'injection' => ['Archive'],
            ],
            '/dial-setting' => [
                'view' => 'dial/setting',
                'js' => 'dial/js/setting.js',
                'injection' => ['DialPeople'],
            ],
            '/dial-mo/:id' => [
                'view' => 'dial/mo',
                'js' => 'dial/js/mo.js',
                'injection' => ['DialPeople'],
            ],
            '/dial-people/:mo/:id' => [
                'view' => 'dial/dial-people',
                'js' => 'dial/js/dial-people.js',
                'injection' => ['DialPeople'],
            ],
            '/eir263' => [
                'view' => 'eir/index',
                'js' => 'eir/js/index.js',
                'injection' => ['Eir'],
            ],
            '/eir263/interview' => [
                'view' => 'eir/interview',
                'js' => 'eir/js/interview.js',
                'injection' => ['Eir'],
            ],
            '/eir263/report' => [
                'view' => 'eir/report',
                'js' => 'eir/js/report.js',
                'injection' => ['Eir'],
            ],
            '/poll' => [
                'view' => 'poll/poll',
                'js' => 'poll/js/poll.js',
                'injection' => ['PollList'],
            ],
            '/poll/list/:id' => [
                'view' => 'poll/list',
                'js' => 'poll/js/list.js',
                'injection' => ['PollList'],
            ],
            '/poll/edit/:id' => [
                'view' => 'poll/edit',
                'js' => 'poll/js/edit.js',
                'injection' => ['PollList'],
            ],
            '/dial/:id' => [
                'view' => 'dial/view',
                'js' => 'dial/js/view.js',
                'injection' => ['DialPeople'],
            ],
            '/:id/edit' => [
                'view' => 'first/update',
                'js' => 'first/js/update.js',
                'injection' => ['Stmt',],
            ],
            '/:id/close' => [
                'view' => 'first/close',
                'js' => 'first/js/close.js',
                'injection' => ['Stmt',],
            ],
            '/:id' => [
                'view' => 'first/view',
                'js' => 'first/js/view.js',
                'injection' => ['Stmt',],
            ],
        ];
    }

    public static function routeOperatorSecond()
    {
        return [
            '/' => [
                'view' => 'index',
                'js' => 'js/index.js',
                'injection' => ['Stmt',],
            ],
            '/create' => [
                'view' => 'create',
                'js' => 'js/create.js',
                'injection' => ['Stmt',],
            ],
            '/report' => [
                'view' => 'report',
                'js' => 'js/report.js',
                'injection' => ['Stmt'],
            ],
            '/statistics' => [
                'view' => 'dial/statistics/report',
                'js' => 'dial/statistics/js/report.js',
                'injection' => ['DialPeople'],
            ],
            '/dial-setting' => [
                'view' => 'dial/setting',
                'js' => 'dial/js/setting.js',
                'injection' => ['DialPeople'],
            ],
            '/archiveMessages' => [
                'view' => 'archive/messages',
                'js' => 'archive/js/messages.js',
                'injection' => ['Archive'],
            ],
            '/poll' => [
                'view' => 'poll/poll',
                'js' => 'poll/js/poll.js',
                'injection' => ['PollList'],
            ],
            '/dsp' => [
                'view' => 'dsp/index',
                'js' => 'dsp/js/index.js',
                'injection' => ['DspPeople'],
            ],
            '/dsp/:mo' => [
                'view' => 'dsp/list',
                'js' => 'dsp/js/list.js',
                'injection' => ['DspPeople'],
            ],
            '/dsp/:mo/:pid' => [
                'view' => 'dsp/people',
                'js' => 'dsp/js/people.js',
                'injection' => ['DspPeople'],
            ],
            '/dial-mo/:id' => [
                'view' => 'dial/mo',
                'js' => 'dial/js/mo.js',
                'injection' => ['DialPeople'],
            ],
            '/dial-people/:mo/:id' => [
                'view' => 'dial/dial-people',
                'js' => 'dial/js/dial-people.js',
                'injection' => ['DialPeople'],
            ],
            '/poll/list/:id' => [
                'view' => 'poll/list',
                'js' => 'poll/js/list.js',
                'injection' => ['PollList'],
            ],
            '/poll/edit/:id' => [
                'view' => 'poll/edit',
                'js' => 'poll/js/edit.js',
                'injection' => ['PollList'],
            ],
            '/monitoring' => [
                'view' => 'monitoring/index',
                'js' => 'monitoring/js/index.js',
                'injection' => ['DialPeople'],
            ],
            '/eir263' => [
                'view' => 'eir/index',
                'js' => 'eir/js/index.js',
                'injection' => ['Eir'],
            ],
            '/eir263/report' => [
                'view' => 'eir/report',
                'js' => 'eir/js/report.js',
                'injection' => ['Eir'],
            ],
            '/eir263/interview' => [
                'view' => 'eir/interview',
                'js' => 'eir/js/interview.js',
                'injection' => ['Eir'],
            ],
            '/interaction' => [
                'view' => 'dial/interaction/interaction',
                'js' => 'dial/interaction/js/interaction.js',
                'injection' => ['Stmt'],
            ],
            '/dial-report' => [
                'view' => 'dial/report/report',
                'js' => 'dial/report/js/report.js',
                'injection' => ['DialPeople'],
            ],
            '/dial' => [
                'view' => 'dial',
                'js' => 'js/dial.js',
                'injection' => ['DialPeople'],
            ],
            '/dial/:id' => [
                'view' => 'dial/view',
                'js' => 'dial/js/view.js',
                'injection' => ['DialPeople'],
            ],
            '/:id/edit' => [
                'view' => 'update',
                'js' => 'js/update.js',
                'injection' => ['Stmt',],
            ],
            '/:id/close' => [
                'view' => 'close',
                'js' => 'js/close.js',
                'injection' => ['Stmt',],
            ],
            '/:id' => [
                'view' => 'view',
                'js' => 'js/view.js',
                'injection' => ['Stmt',],
            ],
        ];
    }

    /**
     * Поля для отчета
     * @return array
     */
    public static function listStmt()
    {
        return array(
            '1' => 'Всего обращений, в том числе:',
            '1.1' => 'по телефону "горячей линии"',
            '1.2' => 'по сети "Интернет"',
            '2' => 'Жалобы',
            '3' => 'Заявлений, всего: в т.ч.:',
            '3.1' => 'о выделении средств для оплаты медицинской помощи в рамках территориальной программы государственных гарантий оказания бесплатной медицинской помощи',
            '3.2' => 'о выборе и замене СМО, в том числе:',
            '3.2.1' => 'о выборе СМО',
            '3.2.2' => 'о замене СМО',
            '3.3' => 'ходатайства о регистрации в качестве застрахованного лица',
            '3.4' => 'ходатайства об идентификации в качестве застрахованного лица',
            '3.5' => 'о выдаче дубликата (переоформлении) полиса ОМС, в том числе:',
            '3.5.1' => 'о переоформлении полиса',
            '3.5.2' => 'о выдаче дубликата полиса',
            '3.6' => 'другие,',
            '3.6.1' => 'в том числе по вопросам, не относящиеся к сфере ОМС',
            '4' => 'Обращения за консультацией (разъяснением), в том числе:',
            '4.1' => 'об обеспечении полисами ОМС, в т.ч.:',
            '4.1.1' => 'об обеспечении полисами ОМС иностранных граждан, беженцев',
            '4.2' => 'о выборе МО в сфере ОМС',
            '4.3' => 'о выборе врача',
            '4.4' => 'о выборе или замене СМО',
            '4.5' => 'о организации работы МО',
            '4.6' => 'о санитарно-гигиеническом состоянии МО',
            '4.7' => 'об этике и диентологии медицинских работников',
            '4.8' => 'о КМП',
            '4.9' => 'о лекарственном обеспечении при оказании медицинской помощи',
            '4.10' => 'об отказе в оказании медицинской помощи по программам ОМС',
            '4.11' => 'о получении медицинской помощи по базовой программе ОМС вне территории страхования',
            '4.12' => 'о взимании денежных средств за медицинскую помощь по программам ОМС',
            '4.12.1' => 'о видах, качестве и условиях предоставления медицинской помощи по программам ОМС',
            '4.13' => 'о платах медицинских услугах, оказываемых в ОМС',
            '4.14' => 'другие',
            '5' => 'Предложения'
        );
    }

    /**
     * Поля таблицы 1.2
     * @return array
     */
    public static function listReport1_2()
    {
        return array(
            '1' => 'Поступило жалоб',
            '2' => 'Причин, указанных в жалобах, всего, в т.ч.:',
            'ж 3' => 'Обеспечение полисами ОМС',
            'ж 4' => 'Выбор МО в сфере ОМС, в т.ч.:',
                'ж 4.1' => 'на территории страхования',
                'ж 4.2' => 'вне территории страхования',
            'ж 5' => 'Выбор врача',
            'ж 6' => 'Выбор или замена СМО, из них:',
                'ж 6.1' => 'по постоянному месту жительства',
                'ж 6.2' => 'вне постоянного места жительства',
                'ж 6.3' => 'без регистрации на территории РФ',
            'ж 7' => 'Организация работы МО',
            'ж 8' => 'Санитарно-гигиеническое состояние МО',
            'ж 9' => 'Материально-техническое обеспечение МО',
            'ж 10' => 'Этика и деонтология медработников',
            'ж 11' => 'КМП',
            'ж 12' => 'Лекарственное обеспечение при оказании медицинской помощи',
            'ж 13' => 'Отказ в медицинской помощи по программам ОМС, всего из них:',
                'ж 13.1' => 'на территории страхования',
                'ж 13.2' => 'вне территории страхования',
            'ж 14' => 'Неисполнение СМО обязанностей по договору',
            'ж 15' => 'Взимание денежных средств за медицинскую помощь по ОМС, всего из них:',
                'ж 15.1' => 'на территории страхования',
                'ж 15.2' => 'вне территории страхования',
            'ж 16' => 'Неправомерное распространение персональных данных',
            'ж 17' => 'Прочие причины',
                'ж 17.1' => 'в том числе по вопросам, не относящимся к сфере ОМС',
        );
    }

    /**
     * @return array
     */
    public static function stopList()
    {
        return array(
            'D8', 'G8',
            'C9', 'F9',
            'C11', 'F11',
            'C12', 'F12',
            'C13', 'F13',
            'C14', 'F14',
            'C15', 'F15',
            'C16', 'F16', 'G16', 'H16',
            'C17', 'F17', 'G17', 'H17',
            'C18', 'F18',
            'C19', 'F19',
            'C20', 'F20',
            'C21', 'F21',
            'C22', 'F22'
        );
    }

    /**
     * Подстановка значений
     * @param $data
     * @param $key
     * @param $company
     * @param $cell
     * @return int|string
     */
    public static function currentValue($data, $key, $company, $cell)
    {
        $stop = Helpers::stopList();
        if(isset($data))
        {
            return in_array($cell, $stop)? 'x': $data[$key];
        }

        return in_array($cell, $stop)? 'x': 0;
    }


    public static function MonthList()
    {
        return array(
            "1"=>"Январь","2"=>"Февраль","3"=>"Март",
            "4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
            "7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
            "10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");
    }
}