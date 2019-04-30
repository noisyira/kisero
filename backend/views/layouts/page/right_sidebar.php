<?php
use kartik\widgets\SideNav;
// OR if this package is installed separately, you can use
// use kartik\sidenav\SideNav;
use yii\helpers\Url;
$type = SideNav::TYPE_DEFAULT;
$page = Yii::$app->controller->action->id;
?>
<?php
echo SideNav::widget([
    'type' => $type,
    'encodeLabels' => false,
    'heading' => false,
    'containerOptions' => [
        ['style' => 'position:none;']
    ],
    'items' => [
        // Important: you need to specify url as 'controller/action',
        // not just as 'controller' even if default action is used.
        ['label' => 'Главная', 'icon' => 'home', 'url' => Url::to(['/']), 'active' => ($page == 'index')],
        ['label' => 'Добавить', 'icon' => 'plus', 'url' => Url::to(['create-stmt']), 'active' => ($page == 'create-stmt')],
        ['label' => 'Сценарии ответов', 'icon' => 'list-alt', 'url' => Url::to(['answer-script']), 'active' => ($page == 'answer-script')],
        ['label' => 'Архив обращений', 'icon' => 'calendar', 'url' => Url::to(['archive']), 'active' => ($page == 'archive')],
        ['label' => 'Отчёт', 'icon' => 'tasks', 'url' => Url::to(['report']), 'active' => ($page == 'report')],
        ['label' => 'Мониторинг', 'icon' => 'time', 'url' => Url::to(['monitoring']), 'active' => ($page == 'monitoring')],
    ],
]);
?>