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


use diecoding\rbac\components\Option;
use diecoding\rbac\components\RouteRule;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $context diecoding\rbac\components\ItemController */

$context = $this->context;
$labels  = $context->labels();
$rules   = Option::authManager()->getRules();

unset($rules[RouteRule::RULE_NAME]);
$source = Json::htmlEncode(array_keys($rules));

$js = <<<JS
$('#rule_name').autocomplete({
  source: $source,
});
JS;
$this->registerJs($js);
?>

<?php $form = ActiveForm::begin(['id' => 'item-form']); ?>
<div class="row">
  <div class="col-sm-6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
  </div>

  <div class="col-sm-6">
    <?= $form->field($model, 'ruleName')->textInput(['id' => 'rule_name']) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
  </div>
</div>

<div class="form-group">
  <?= Html::submitButton($model->isNewRecord ? Yii::t('diecoding', 'Create') : Yii::t('diecoding', 'Update'), [
    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
    'name' => 'submit-button'
  ])
  ?>
</div>

<?php ActiveForm::end(); ?>
