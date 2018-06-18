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
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model diecoding\rbac\models\User
 * @var $form yii\widgets\ActiveForm
 */

?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'status')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('diecoding', 'Create') : Yii::t('diecoding', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
