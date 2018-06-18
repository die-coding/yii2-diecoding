<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

namespace diecoding\rbac\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\mail\BaseMailer;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use diecoding\rbac\models\form\Login;
use diecoding\rbac\models\form\PasswordResetRequest;
use diecoding\rbac\models\form\ResetPassword;
use diecoding\rbac\models\form\Signup;
use diecoding\rbac\models\form\ChangePassword;
use diecoding\rbac\models\searchs\User as UserSearch;

/**
 * User controller
 */
class UserController extends Controller
{
  const MAILER_PATH = "@diecoding/rbac/mail";

  public $userClassName;

  private $_oldMailerPath;

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
          'delete'   => ['post'],
          'logout'   => ['post'],
          'activate' => ['post'],
        ],
      ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function beforeAction($action)
  {
    if (parent::beforeAction($action)) {
      if (Yii::$app->has('mailer') && ($mailer = Yii::$app->mailer) instanceof BaseMailer) {

        /**
         * @var $mailer BaseMailer
         */
        $this->_oldMailerPath = $mailer->getViewPath();
        $mailer->setViewPath(self::MAILER_PATH);
      }

      return true;
    }

    return false;
  }

  /**
   * @inheritdoc
   */
  public function afterAction($action, $result)
  {
    if ($this->_oldMailerPath !== null) {
      Yii::$app->mailer->setViewPath($this->_oldMailerPath);
    }

    return parent::afterAction($action, $result);
  }

  /**
   * Lists all User models.
   * @return mixed
   */
  public function actionIndex()
  {
    $searchModel = new UserSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->userClassName);

    return $this->render('index', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single User model.
   * @param integer $id
   * @return mixed
   */
  public function actionView($id)
  {
    return $this->render('view', [
      'model' => $this->findModel($id),
    ]);
  }

  /**
   * Deletes an existing $this->userClassName model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   */
  public function actionDelete($id)
  {
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
  }

  /**
   * Login
   * @return string
   */
  public function actionLogin()
  {
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $model = new Login();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
      return $this->goBack();
    } else {
      return $this->render('login', [
        'model' => $model,
      ]);
    }
  }

  /**
   * Logout
   * @return string
   */
  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }

  /**
   * Signup new user
   * @return string
   */
  public function actionSignup()
  {
    $model = new Signup();
    if ($model->load(Yii::$app->request->post())) {
      if ($user = $model->signup()) {
        return $this->goHome();
      }
    }

    return $this->render('signup', [
      'model' => $model,
    ]);
  }

  /**
   * Request reset password
   * @return string
   */
  public function actionRequestPasswordReset()
  {
    $model = new PasswordResetRequest();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->sendEmail()) {
        Yii::$app->session->setFlash('success', Yii::t('diecoding', 'Check your email for further instructions.'));

        return $this->goHome();
      } else {
        Yii::$app->session->setFlash('error', Yii::t('diecoding', 'Sorry, we are unable to reset password for email provided.'));
      }
    }

    return $this->render('requestPasswordResetToken', [
      'model' => $model,
    ]);
  }

  /**
   * Reset password
   * @return string
   */
  public function actionResetPassword($token)
  {
    try {
      $model = new ResetPassword($token);
    } catch (InvalidParamException $e) {
      throw new BadRequestHttpException($e->message);
    }

    if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
      Yii::$app->session->setFlash('success', Yii::t('diecoding', 'New password was saved.'));

      return $this->goHome();
    }

    return $this->render('resetPassword', [
      'model' => $model,
    ]);
  }

  /**
   * Reset password
   * @return string
   */
  public function actionChangePassword()
  {
    $model = new ChangePassword();
    if ($model->load(Yii::$app->request->post()) && $model->change()) {
      return $this->goHome();
    }

    return $this->render('change-password', [
      'model' => $model,
    ]);
  }

  /**
   * Activate new user
   * @param integer $id
   * @return type
   * @throws UserException
   * @throws NotFoundHttpException
   */
  public function actionActivate($id)
  {
    $user = $this->findModel($id);

    if ($user->status == $this->userClassName::STATUS_ACTIVE) {
      $user->status = $this->userClassName::STATUS_INACTIVE;

    } elseif ($user->status == $this->userClassName::STATUS_INACTIVE) {
      $user->status = $this->userClassName::STATUS_ACTIVE;
    }

    if (!$user->save()) {
      $errors = $user->firstErrors;
      throw new UserException(reset($errors));
    }

    return $this->redirect(['index']);
  }

  /**
   * Finds the User model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return $this->userClassName the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = $this->userClassName::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('diecoding', 'The requested page does not exist.'));
  }
}
