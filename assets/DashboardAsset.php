<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DashboardAsset extends AssetBundle
{
    public $basePath    = '@webroot';
    public $baseUrl     = '@web';
    public $baseVendor  = '@vendor';
    public $css = [
        'css/bootstrap.min.css',
        'css/sb-admin.css',
        'css/font-awesome/css/font-awesome.min.css',
        'css/site.css',
        'css/tableexport.min.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/custom.js',
        'js/jquery.table2excel.js',
        'js/exportcustom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
