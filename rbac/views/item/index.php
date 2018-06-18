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
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel diecoding\rbac\models\searchs\AuthItem */
/* @var $context diecoding\rbac\components\ItemController */

$context     = $this->context;
$labels      = $context->labels();

$this->title = $labels['Items'];
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Option::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);

?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
  <?= Html::a(Yii::t('diecoding', 'Create {value}', ['value' => $labels['Item']]), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class="table-responsive">

  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      [
        'attribute' => 'name',
        'label'     => Yii::t('diecoding', 'Name'),
      ],
      [
        'attribute' => 'ruleName',
        'label'     => Yii::t('diecoding', 'Rule Name'),
        'filter'    => $rules
      ],
      [
        'attribute' => 'description',
        'label'     => Yii::t('diecoding', 'Description'),
      ],

      [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{view}',
      ],
    ],
  ])
  ?>

</div>
