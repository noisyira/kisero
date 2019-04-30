<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SipSecondAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'css/custom.css',
        'css/libs.min.css'
    ];
    public $js = [
        'js/SIPml.js',
        'js/SIPml-api.js?svn=250',
        'js/angular/operatorSecondLevel/app.js',
        'js/microphone.js'
    ];
    public $jsOptions = ['position' => View::POS_HEAD];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\AngularAsset',
        'backend\assets\FontAwesomeAsset'
    ];
}

