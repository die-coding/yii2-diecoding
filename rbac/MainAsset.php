<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

namespace diecoding\rbac;

use yii\web\AssetBundle;

/**
 * Main Asset.
 * This is main assets for this package
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class MainAsset extends AssetBundle
{

  /**
   * @inheritdoc
   */
  public $sourcePath = '@diecoding/rbac/assets';

  /**
   * @inheritdoc
   */
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
    'yii\bootstrap\BootstrapPluginAsset',
  ];

  /**
   * @inheritdoc
   */
  public function init()
  {

    // In dev mode use non-minified
    $this->js = YII_ENV_DEV ? [
      'jquery-ui/jquery-ui.js',
      'js/script.js',
    ] : [
      'jquery-ui/jquery-ui.min.js',
      'js/script.min.js',
    ];

    $this->css = YII_ENV_DEV ? [
      'jquery-ui/jquery-ui.css',
      'css/style.css',
    ] : [
      'jquery-ui/jquery-ui.min.css',
      'css/style.min.css',
    ];

    parent::init();
  }

}
