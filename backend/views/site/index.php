<?php

use dee\angular\NgView;
use yii\web\View;
use yii\helpers\Url;

/* @var $this View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    site index
    <?= Url::to('/operator/stmt')?>
    <?= NgView::widget([
        'name' => 'myapp', // default dApp
        'routes'=>[
            '/'=>[
                'redirectTo' => '/stmt',
            ],
            '/stmt'=>[
                'view'=> Url::to('/operator/stmt').'/index',
                'js'=>Url::to('/operator/stmt').'/js/index.js',
                'injection'=>['$location', '$routeParams'], // $scope and $injector are always be added
            ],
        ]
    ])?>
</div>