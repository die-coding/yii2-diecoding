<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel diecoding\rbac\models\searchs\Menu */

$this->title = Yii::t('diecoding', 'Menus');

$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
  <?= Html::a(Yii::t('diecoding', 'Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class="table-responsive">

  <?php Pjax::begin(); ?>
  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      'id',
      'name',
      [
        'attribute' => 'menuParent.name',
        'filter'    => Html::activeTextInput($searchModel, 'parent_name', [
          'class' => 'form-control', 'id' => null
        ]),
        'label' => Yii::t('diecoding', 'Parent'),
      ],
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
        'filter' => $searchModel->getListAssign(),
      ],
      [
        'attribute' => 'visible',
        'value'     => function($model) {
          $value = [
            0 => Yii::t('diecoding', 'Inactive'),
            1 => Yii::t('diecoding', 'Active'),
          ];

          return !array_key_exists($model->visible, $value) ? : $value[$model->visible];
        },
        'filter' => [
          0 => Yii::t('diecoding', 'Inactive'),
          1 => Yii::t('diecoding', 'Active'),
        ],
      ],
      'order',
      ['class' => 'yii\grid\ActionColumn'],
    ],
  ]); ?>
  <?php Pjax::end(); ?>

</div>
