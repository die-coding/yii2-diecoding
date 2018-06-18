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


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel diecoding\rbac\models\searchs\Assignment
 * @var $usernameField string
 * @var $extraColumns string[]
 */

$this->title = Yii::t('diecoding', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
  ['class' => 'yii\grid\SerialColumn'],
  $usernameField,
];

if (!empty($extraColumns)) {
  $columns = array_merge($columns, $extraColumns);
}

$columns[] = [
  'class'    => 'yii\grid\ActionColumn',
  'template' => '{view}'
];
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="table-responsive">

  <?php Pjax::begin(); ?>

  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => $columns,
  ]);
  ?>

  <?php Pjax::end(); ?>

</div>
