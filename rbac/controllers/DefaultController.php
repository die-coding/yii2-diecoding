<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 20 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 11 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\controllers;

use Yii;
use yii\web\Controller;

/**
 * DefaultController
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class DefaultController extends Controller
{

  /**
   * Action index
   */
  public function actionIndex($page = 'README.md')
  {
    if (strpos($page, '.md') === false) {
      $file = Yii::getAlias("@diecoding/rbac/guide/{$page}");
      return Yii::$app->response->sendFile($file);
    }

    return $this->render('index', [
      'page' => $page,
    ]);
  }
}
