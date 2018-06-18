<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 11 March 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 11 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \diecoding\rbac\models\form\Signup
 */

$this->title = Yii::t('diecoding', 'Signup');
$this->params['breadcrumbs'][] = $this->title;

?>

  <h1><?= Html::encode($this->title) ?></h1>

  <p><?= Yii::t('diecoding', 'Please fill out the following fields to signup:') ?></p>

  <?= Html::errorSummary($model)?>
  
  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
      <?= $form->field($model, 'username') ?>
      <?= $form->field($model, 'email') ?>
      <?= $form->field($model, 'password')->passwordInput() ?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('diecoding', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
      </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
