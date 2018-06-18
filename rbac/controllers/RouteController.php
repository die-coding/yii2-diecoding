<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 11 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\controllers;

use Yii;
use diecoding\rbac\models\Route;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Description of RuleController
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class RouteController extends Controller
{
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'create'  => ['post'],
          'assign'  => ['post'],
          'remove'  => ['post'],
          'refresh' => ['post'],
        ],
      ],
    ];
  }

  /**
   * Lists all Route models.
   * @return mixed
   */
  public function actionIndex()
  {
    $model = new Route();
    return $this->render('index', [
      'routes' => $model->getRoutes(),
    ]);
  }

  /**
   * Creates a new AuthItem model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    Yii::$app->response->format = 'json';
    $routes = Yii::$app->request->post('route', '');
    $routes = preg_split('/\s*,\s*/', trim($routes), -1, PREG_SPLIT_NO_EMPTY);

    $model  = new Route();
    $model->addNew($routes);
    return $model->getRoutes();
  }

  /**
   * Assign routes
   * @return array
   */
  public function actionAssign()
  {
    $routes = Yii::$app->request->post('routes', []);
    $model  = new Route();

    $model->addNew($routes);
    Yii::$app->response->format = 'json';
    return $model->getRoutes();
  }

  /**
   * Remove routes
   * @return array
   */
  public function actionRemove()
  {
    $routes = Yii::$app->request->post('routes', []);
    $model  = new Route();

    $model->remove($routes);
    Yii::$app->response->format = 'json';
    return $model->getRoutes();
  }

  /**
   * Refresh cache
   * @return type
   */
  public function actionRefresh()
  {
    $model = new Route();
    $model->invalidate();

    Yii::$app->response->format = 'json';
    return $model->getRoutes();
  }
}
