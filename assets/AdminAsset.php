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
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'loserbackstage/css/bootstrap/bootstrap.css',
        'loserbackstage/css/bootstrap/bootstrap-responsive.css',
        'loserbackstage/css/bootstrap/bootstrap-overrides.css',
        'loserbackstage/css/lib/jquery-ui-1.10.2.custom.css',
        'loserbackstage/css/lib/font-awesome.css',
        'loserbackstage/css/layout.css',
        'loserbackstage/css/elements.css',
        'loserbackstage/css/icons.css',
        'loserbackstage/css/compiled/index.css',
        'loserbackstage/css/lib/bootstrap-wysihtml5.css',
        'loserbackstage/css/compiled/form-showcase.css',
    ];
    public $js = [
        'loserbackstage/js/bootstrap.min.js',
        'loserbackstage/js/jquery-ui-1.10.2.custom.min.js',
        'loserbackstage/js/jquery.knob.js',
        'loserbackstage/js/jquery.flot.js',
        'loserbackstage/js/jquery.flot.stack.js',
        'loserbackstage/js/jquery.flot.resize.js',
        'loserbackstage/js/theme.js',
        'loserbackstage/js/wysihtml5-0.3.0.js',
        'loserbackstage/js/bootstrap-wysihtml5-0.0.2.js',
        ['http://html5shim.googlecode.com/svn/trunk/html5.js', 'condition' => 'lte IE9', 'position' => \yii\web\View::POS_HEAD],
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
