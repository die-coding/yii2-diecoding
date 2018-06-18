<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 10 March 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 15 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\Menu */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
  <?= Html::a(Yii::t('diecoding', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
  <?= Html::a(Yii::t('diecoding', 'Delete'), ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
      'confirm' =>  Yii::t('diecoding', 'Are you sure to delete this item?'),
      'method' => 'post',
    ],
  ])
  ?>
  <?= Html::a(Yii::t('diecoding', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class="table-responsive">

  <?=
  DetailView::widget([
    'model' => $model,
    'attributes' => [
      'menuParent.name:text:Parent',
      'name',
      [
        'attribute' => 'route',
        'format'    => 'raw',
        'value'     => function($model) {
          return $model->route ? Html::a($model->route, [$model->route], ['target' => '_blank']) : null;
        }
      ],
      'icon',
      [
        'attribute' => 'assign',
        'value' => function($model) {
          return $model->getListAssign($model->assign);
        },
      ],
      [
        'attribute' => 'visible',
        'value'     => function($model) {
          $value = [
            0 => Yii::t('diecoding', 'Inactive'),
            1 => Yii::t('diecoding', 'Active'),
          ];

          return !array_key_exists($model->visible, $value) ? : $value[$model->visible];
        }
      ],
      'order',
      'data:ntext',
    ],
  ])
  ?>

</div>
