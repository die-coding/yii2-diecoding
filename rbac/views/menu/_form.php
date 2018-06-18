<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 08 March 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 15 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


use diecoding\rbac\models\Menu;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\Menu */
/* @var $form yii\widgets\ActiveForm */

$items = Json::htmlEncode([
  'menus'  => Menu::getMenuSource(),
  'routes' => Menu::getSavedRoutes(),
]);
$this->registerJs($this->render('_script.php', ['items' => $items]));

?>

<?php $form = ActiveForm::begin(); ?>

<?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>

<div class="row">
  <div class="col-sm-6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name']) ?>

    <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => 32]) ?>
  </div>
  <div class="col-sm-6">
    <?= $form->field($model, 'assign')->dropdownList($model->getListAssign()) ?>

    <?= $form->field($model, 'visible')->dropdownList([
      1 => Yii::t('diecoding', 'Active'),
      0 => Yii::t('diecoding', 'Inactive'),
    ]
    ) ?>

    <?= $form->field($model, 'order')->input('number') ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
  </div>
</div>

<div class="form-group">
  <?=
  Html::submitButton($model->isNewRecord ? Yii::t('diecoding', 'Create') : Yii::t('diecoding', 'Update'), ['class' => $model->isNewRecord
  ? 'btn btn-success' : 'btn btn-primary'])
  ?>
</div>

<?php ActiveForm::end(); ?>
