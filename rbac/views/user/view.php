<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 10 March 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 11 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */



use yii\helpers\Html;
use yii\widgets\DetailView;
use diecoding\rbac\components\Helper;

/**
 * @var $this yii\web\View
 * @var $model diecoding\rbac\models\User
 */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
  <?php if (!Yii::$app->user->isGuest && (int) $model->id !== Yii::$app->user->id) {

    if (Helper::checkRoute($controllerId . 'activate')) {
      $label  =  Yii::t('diecoding', 'Activate');
      if ($model->status == $model::STATUS_ACTIVE) {
        $label  = Yii::t('diecoding', 'Non Activate');
      }

      echo Html::a($label, ['activate', 'id' => $model->id], [
        'class' => 'btn btn-primary',
        'data' => [
          'confirm' => Yii::t('diecoding', 'Are you sure you want to {label} this user?', ['label' => strtolower($label)]),
          'method' => 'post',
        ],
      ]);

      echo " "; // Space between button
    }

    if (Helper::checkRoute($controllerId . 'delete')) {
      echo Html::a(Yii::t('diecoding', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
          'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
          'method' => 'post',
        ],
      ]);
    }
  }
  ?>
</p>

<div class="table-responsive">
  <?=
  DetailView::widget([
    'model' => $model,
    'attributes' => [
      'username',
      'email:email',
      'created_at:date',
      'status',
    ],
  ])
  ?>
</div>
