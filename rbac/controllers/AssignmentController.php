<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/

namespace diecoding\rbac\controllers;

use Yii;
use diecoding\rbac\components\Assign;
use diecoding\rbac\models\Assignment;
use diecoding\rbac\models\searchs\Assignment as AssignmentSearch;
use diecoding\rbac\models\searchs\User as UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
* AssignmentController implements the CRUD actions for Assignment model.
*
* @author Die Coding <diecoding@gmail.com>
* @since 0.0.0
*/
class AssignmentController extends Controller
{
  public $userClassName;
  public $idField            = 'id';
  public $usernameField      = 'username';
  public $fullnameField;
  public $searchClass;
  public $extraColumns       = [];
  public $isEmailFieldActive = true;

  /**
  * @inheritdoc
  */
  public function init()
  {
    parent::init();
    if ($this->userClassName === null) {
      $this->userClassName = Yii::$app->user->identityClass;
      $this->userClassName = $this->userClassName ? : 'diecoding\rbac\models\User';
    }
  }

  /**
  * @inheritdoc
  */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'assign' => ['post'],
          'assign' => ['post'],
          'revoke' => ['post'],
        ],
      ],
    ];
  }

  /**
  * Lists all Assignment models.
  * @return mixed
  */
  public function actionIndex()
  {
    if ($this->searchClass === null) {
      $searchModel  = new AssignmentSearch;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->userClassName, $this->usernameField);
    } else {
      $class        = $this->searchClass;
      $searchModel  = new $class;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    }

    if (empty($extraColumns = $this->extraColumns)) {
      if ($this->isEmailFieldActive) {
        $extraColumns = [
          [
            'attribute' => 'email',
            'label'     => Yii::t('diecoding', 'Email'),
            'format'    => 'email',
          ],
        ];
      }

      $extraColumns[] = [
        'label'  => Yii::t('diecoding', 'Roles'),
        'format' => 'ntext',
        'value'  => function ($model, $key, $index, $column) {
          $access = new Assign;
          return $access->print($model->id);
        },
      ];

      $this->searchClass = 'diecoding\rbac\models\searchs\User';
    }

    return $this->render('index', [
      'dataProvider'  => $dataProvider,
      'searchModel'   => $searchModel,
      'idField'       => $this->idField,
      'usernameField' => $this->usernameField,
      'extraColumns'  => $extraColumns,
    ]);
  }

  /**
  * Displays a single Assignment model.
  * @param  integer $id
  * @return mixed
  */
  public function actionView($id)
  {
    $model = $this->findModel($id);

    return $this->render('view', [
      'model'         => $model,
      'idField'       => $this->idField,
      'usernameField' => $this->usernameField,
      'fullnameField' => $this->fullnameField,
    ]);
  }

  /**
  * Assign items
  * @param string $id
  * @return array
  */
  public function actionAssign($id)
  {
    $items   = Yii::$app->request->post('items', []);
    $model   = new Assignment($id);
    $success = $model->assign($items);
    Yii::$app->response->format = 'json';

    return array_merge($model->getItems(), ['success' => $success]);
  }

  /**
  * Assign items
  * @param string $id
  * @return array
  */
  public function actionRevoke($id)
  {
    $items   = Yii::$app->request->post('items', []);
    $model   = new Assignment($id);
    $success = $model->revoke($items);
    Yii::$app->response->format = 'json';

    return array_merge($model->getItems(), ['success' => $success]);
  }

  /**
  * Finds the Assignment model based on its primary key value.
  * If the model is not found, a 404 HTTP exception will be thrown.
  * @param  integer $id
  * @return Assignment the loaded model
  * @throws NotFoundHttpException if the model cannot be found
  */
  protected function findModel($id)
  {
    $class = $this->userClassName;
    if (($user = $class::findIdentity($id)) !== null) {
      return new Assignment($id, $user);
    } else {
      throw new NotFoundHttpException(Yii::t('diecoding', 'The requested page does not exist.'));
    }
  }
}
