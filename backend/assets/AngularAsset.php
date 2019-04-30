<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\View;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'angular-ui-select/dist/select.min.css',
        'angular-busy/dist/angular-busy.min.css',
//        'angular-flash-alert/dist/angular-flash.min.css',
//        'angular-xeditable/dist/css/xeditable.css',
        'angular-bootstrap/ui-bootstrap-csp.css',
//        'fontawesome/css/font-awesome.min.css',
        'angular-motion/dist/angular-motion.min.css',
//        'angular-motion/dist/modules/collapse.min.css',
//        'ng-table/dist/ng-table.min.css',
        'bootstrap-daterangepicker/daterangepicker.css',
        'oi.select/dist/select.min.css',
        'angular-ui-notification/dist/angular-ui-notification.min.css',
    ];
    public $js = [
//        'angular/angular.js',
        'angular-animate/angular-animate.min.js',
        'angular-sanitize/angular-sanitize.min.js',
        'angular-strap/dist/angular-strap.min.js',
        'angular-strap/dist/angular-strap.tpl.min.js',
        'oi.select/dist/select.min.js',
        'oi.select/dist/select-tpls.min.js',
        'angular-bootstrap/ui-bootstrap.min.js',
        'angular-bootstrap/ui-bootstrap-tpls.min.js',
        'ng-tasty/ng-tasty.min.js',
        'ng-tasty/ng-tasty-tpls.min.js',
        'videogular/videogular.min.js',
        'videogular-controls/vg-controls.min.js',
//        'angular-ui-mask/dist/mask.min.js',
//        'angular-route/angular-route.js',
        'angular-ui-select/dist/select.js',
        'angular-ui-notification/dist/angular-ui-notification.min.js',
        'angular-cookies/angular-cookies.min.js',
        'angular-busy/dist/angular-busy.min.js',
        'angular-file-upload/dist/angular-file-upload.min.js',
//        'angular-flash-alert/dist/angular-flash.min.js',
//        'angular-timer/dist/angular-timer.min.js',
//        'angular-xeditable/dist/js/xeditable.js',
//        'angular-file-saver/dist/angular-file-saver.min.js',
//        'angular-file-saver/dist/angular-file-saver.bundle.min.js',
        'moment/min/moment.min.js',
        'moment/locale/ru.js',
        'bootstrap-daterangepicker/daterangepicker.js',
        'angular-daterangepicker/js/angular-daterangepicker.min.js',
//        'ng-table/dist/ng-table.js',
        'angular-i18n/angular-locale_ru-ru.js',
//        'spin.js/spin.js',
//        'angular-spinner/angular-spinner.js'
    ];
    
    public $jsOptions = [
//        'position' => View::POS_HEAD,
    ];
}
