<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 12 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


use diecoding\rbac\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\AuthItem */
/* @var $context diecoding\rbac\components\ItemController */

$context     = $this->context;
$labels      = $context->labels();
$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => $labels['Items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$items = Json::htmlEncode([
  'items' => $model->getItems(),
]);

$this->registerJs($this->render('_script', ['items' => $items]));

$loading = '<i class="glyphicon glyphicon-refresh icon-refresh-animate"></i>';
$btn     = ['<i class ="glyphicon icon-success refresh"></i>', '<i class="glyphicon icon-danger refresh"></i>'];

?>

<h1><?=Html::encode($this->title);?></h1>

<p>
  <?= Html::a(Yii::t('diecoding', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
  <?= Html::a(Yii::t('diecoding', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('diecoding', 'Are you sure to delete this item?'),
    'data-method' => 'post',
  ]) ?>
  <?= Html::a(Yii::t('diecoding', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="row">
  <div class="col-sm-12">

    <div class="table-responsive">
      <?=
      DetailView::widget([
        'model' => $model,
        'attributes' => [
          'name',
          'description:ntext',
          'ruleName',
          'data:ntext',
        ],
      ])
      ?>
    </div>

  </div>
</div>

<br>

<div class="row">
  <div class="col-xs-12 col-sm-5">
    <div class="input-group">
      <input class="form-control search" data-target="available" placeholder="<?= Yii::t('diecoding', 'Search for available') ?>">
      <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
    </div>
    <br>
    <select multiple size="29" class="form-control list" data-target="available"></select>
  </div>
  <div class="col-xs-12 col-sm-2 text-center">
    <div class="btn-group-vertical btn-group-lg btn-group-assign">

      <?= Html::a("{$btn[0]}{$loading}", ['assign', 'id' => $model->name], [
        'class'       => 'btn btn-success btn-lg btn-assign',
        'data-target' => 'available',
        'title'       => Yii::t('diecoding', 'Assign'),
      ]
      ) ?>
      <?= Html::a("{$btn[1]}{$loading}", ['remove', 'id' => $model->name], [
        'class'       => 'btn btn-danger btn-lg btn-assign',
        'data-target' => 'assigned',
        'title'       => Yii::t('diecoding', 'Remove'),
      ]
      ) ?>
    </div>
  </div>
  <div class="col-xs-12 col-sm-5">
    <div class="input-group">
      <input class="form-control search" data-target="assigned" placeholder="<?= Yii::t('diecoding', 'Search for assigned') ?>">
      <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
    </div>
    <br>
    <select multiple size="29" class="form-control list" data-target="assigned"></select>
  </div>
</div>
