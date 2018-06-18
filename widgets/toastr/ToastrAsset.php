<?php
# @Author: Die Coding | www.diecoding.com
# @Date:   23 January 2018
# @Email:  diecoding@gmail.com
# @Last modified by:   Die Coding | www.diecoding.com
# @Last modified time: 29 January 2018
# @License: MIT
# @Copyright: 2018



namespace diecoding\widgets\toastr;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle
{
    /** @var string $sourcePath  */
    public $sourcePath = '@bower/toastr';

    /** @var array $css */
    public $css = [
        YII_ENV_DEV ? 'toastr.css' : 'toastr.min.css',
    ];

    /** @var array $js */
    public $js = [
        YII_ENV_DEV ? 'toastr.js' : 'toastr.min.js',
    ];

    /** @var array $depends */
    public $depends = [
        'yii\web\YiiAsset',
    //     'yii\bootstrap\BootstrapAsset',
    ];
}
