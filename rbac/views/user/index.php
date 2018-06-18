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
use diecoding\rbac\components\Helper;

/**
 * @var $this yii\web\View
 * @var $searchModel diecoding\rbac\models\searchs\User
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('diecoding', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="table-responsive">

  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      'username',
      // 'email:email',
      'created_at:date',
      [
        'attribute' => 'status',
        'value' => function($model) {
          return $model->status == 0 ? Yii::t('diecoding', 'Inactive') : Yii::t('diecoding', 'Active');
        },
        'filter' => [
          0  => Yii::t('diecoding', 'Inactive'),
          10 => Yii::t('diecoding', 'Active'),
        ]
      ],
      [
        'class' => 'yii\grid\ActionColumn',
        'template' => Helper::filterActionColumn(['view', 'activate', 'delete']),
        'buttons' => [
          'activate' => function($url, $model) {

            $label  =  Yii::t('diecoding', 'Activate');
            $button = '<span class="glyphicon glyphicon-ok"></span>';

            if ($model->status == $model::STATUS_ACTIVE) {
              $label  = Yii::t('diecoding', 'Non Activate');
              $button = '<span class="glyphicon glyphicon-remove"></span>';
            }

            $options = [
              'title'        => $label,
              'aria-label'   => $label,
              'data-confirm' => Yii::t('diecoding', 'Are you sure you want to {label} this user?', ['label' => strtolower($label)]),
              'data-method'  => 'post',
              'data-pjax'    => '0',
            ];

            return Html::a($button, $url, $options);
          }
        ],
        'visibleButtons' => [
          'activate' => function ($model) {
            return !Yii::$app->user->isGuest && (int) $model->id !== Yii::$app->user->id;
          },
          'delete' => function ($model) {
            return !Yii::$app->user->isGuest && (int) $model->id !== Yii::$app->user->id;
          }
        ]
      ],
    ],
  ]);
  ?>

</div>
