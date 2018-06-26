<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/


namespace diecoding\components;

use Yii;
use DOMDocument;
use yii\base\Component;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use common\models\Options;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class Option extends Component
{
  const THEME_PATH_BASE    = "@themes/";
  const THEME_MAIN_LAYOUTS = "/layouts/main.php";
  const THEME_ACTIVE_DIR   = "/assets";
  const DEFAULT_THEME_NAME = "diecoding";
  const DEFAULT_THEME_BASE = "@web";

  public function __get($function)
  {
    return $this->$function();
  }

  public function app($nama)
  {
    return $this->findModel($nama, "app");
  }

  public function extends($nama, $extends)
  {
    return $this->findModel($nama, "extends", $extends);
  }

  public function isFrontend()
  {
    return Yii::$app->id === Yii::$app->params["app.frontend.id"];
  }

  public function isBackend()
  {
    return Yii::$app->id === Yii::$app->params["app.backend.id"];
  }

  public function getFormat($nama)
  {
    if (($model = Options::find()->select(["format"])->where(["nama" => $nama])->one()) !== null) {
      return $model->format;
    } else {
      throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
    }
  }

  public function themeActive()
  {
    $app   = $this->isFrontend() ? "frontend" : "backend";
    $nama  = "t_{$app}";
    $value = $this->findModel($nama, "themeActive", "theme");
    $theme_dir  = self::THEME_PATH_BASE . $value . self::THEME_MAIN_LAYOUTS;
    $theme_path = Yii::getAlias($theme_dir);

    if (!is_file($theme_path)) {
      echo "<code>Directory: {$theme_path} tidak tersedia.</code>";
      exit;
    }

    return $theme_dir;
  }

  public function themeActiveDir()
  {
    $app       = $this->isFrontend() ? "frontend" : "backend";
    $nama      = "t_{$app}";
    $value     = $this->findModel($nama, "themeActive", "theme");
    $dirAssets = Yii::getAlias(self::THEME_PATH_BASE . $value . self::THEME_ACTIVE_DIR);

    if ($value === self::DEFAULT_THEME_NAME) {
      return self::DEFAULT_THEME_BASE;
    } elseif (!is_dir($dirAssets)) {
      return Yii::getAlias("@public/themes/{$value}");
    } else {
      return Yii::$app->assetManager->getPublishedUrl($dirAssets);
    }
  }

  public function themePreviews()
  {
    $dir      = self::THEME_PATH_BASE . 'previews';
    $dirAlias = Yii::getAlias($dir);

    if (!is_dir(Yii::$app->assetManager->getPublishedPath($dirAlias))) {
      Yii::$app->assetManager->publish($dirAlias);
    }

    $files = FileHelper::findFiles($dirAlias, [
      'only' => [
        '*.png',
      ]
    ]);

    $out = [0 => ['name' => '', 'url' => '']];
    foreach ($files as $i => $file) {
      $filename = pathinfo($file)['filename'];
      $base     = pathinfo($file)['basename'];

      $out[$i] = [
        'dirOriginal' => $dir,
        'dirAlias'    => $dirAlias,
        'name'        => $filename,
        'url'         => Yii::$app->assetManager->getPublishedUrl($dirAlias) . '/' . $base,
      ];
    }

    return $out;
  }

  public function listMenu($function = "main")
  {
    $model = new ListMenu();
    return $model->$function();
  }

  protected function findModel($nama, $function, $extends = null)
  {
    if (($model = Options::find()->select(["value"])->where(["nama" => $nama, "extends" => $extends])->one()) !== null) {
      return $model->value;
    }

    if (YII_ENV_DEV) {
      throw new BadRequestHttpException("Option::{$function}(\"{$nama}\") - options.nama = {$nama} tidak ditemukan.");
    }

    throw new NotFoundHttpException(Yii::t("app", "Mohon refresh halaman ini."));
  }
}
