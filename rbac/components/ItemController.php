<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 07 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\components;

use Yii;
use diecoding\rbac\models\AuthItem;
use diecoding\rbac\models\searchs\AuthItem as AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;
use yii\filters\VerbFilter;
use yii\rbac\Item;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @property integer $type
 * @property array $labels
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class ItemController extends Controller
{
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
          'assign' => ['post'],
          'remove' => ['post'],
        ],
      ],
    ];
  }

  /**
   * Lists all AuthItem models.
   * @return mixed
   */
  public function actionIndex()
  {
    $searchModel  = new AuthItemSearch([
      'type' => $this->type,
    ]);
    $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

    return $this->render('index', [
      'dataProvider' => $dataProvider,
      'searchModel'  => $searchModel,
    ]);
  }

  /**
   * Displays a single AuthItem model.
   * @param  string $id
   * @return mixed
   */
  public function actionView($id)
  {
    $model = $this->findModel($id);

    return $this->render('view', [
      'model' => $model,
    ]);
  }

  /**
   * Creates a new AuthItem model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model       = new AuthItem(null);
    $model->type = $this->type;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->name]);
    } else {
      return $this->render('create', ['model' => $model]);
    }
  }

  /**
   * Updates an existing AuthItem model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param  string $id
   * @return mixed
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->name]);
    }

    return $this->render('update', ['model' => $model]);
  }

  /**
   * Deletes an existing AuthItem model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param  string $id
   * @return mixed
   */
  public function actionDelete($id)
  {
    $model = $this->findModel($id);

    Option::authManager()->remove($model->item);
    Helper::invalidate();

    return $this->redirect(['index']);
  }

  /**
   * Assign items
   * @param string $id
   * @return array
   */
  public function actionAssign($id)
  {
    $items   = Yii::$app->request->post('items', []);
    $model   = $this->findModel($id);
    $success = $model->addChildren($items);

    Yii::$app->response->format = 'json';

    return array_merge($model->getItems(), ['success' => $success]);
  }

  /**
   * Assign or remove items
   * @param string $id
   * @return array
   */
  public function actionRemove($id)
  {
    $items   = Yii::$app->request->post('items', []);
    $model   = $this->findModel($id);
    $success = $model->removeChildren($items);
    Yii::$app->response->format = 'json';

    return array_merge($model->getItems(), ['success' => $success]);
  }

  /**
   * @inheritdoc
   */
  public function getViewPath()
  {
    return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'item';
  }

  /**
   * Label use in view
   * @throws NotSupportedException
   */
  public function labels()
  {
    throw new NotSupportedException(get_class($this) . Yii::t('diecoding', ' does not support labels().'));
  }

  /**
   * Type of Auth Item.
   * @return integer
   */
  public function getType()
  {

  }

  /**
   * Finds the AuthItem model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param string $id
   * @return AuthItem the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    $auth = Option::authManager();
    $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
    if ($item) {
      return new AuthItem($item);
    } else {
      throw new NotFoundHttpException(Yii::t('diecoding', 'The requested page does not exist.'));
    }
  }
}
