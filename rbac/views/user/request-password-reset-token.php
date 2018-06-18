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
 * @var $model \diecoding\rbac\models\form\PasswordResetRequest
 */

$this->title = Yii::t('diecoding', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<p><?= Yii::t('diecoding', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

<div class="row">
  <div class="col-lg-5">
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
    <?= $form->field($model, 'email') ?>
    <div class="form-group">
      <?= Html::submitButton(Yii::t('diecoding', 'Send'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>
