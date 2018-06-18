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
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var diecoding\rbac\models\AuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
  <?= Html::a(Yii::t('diecoding', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
  <?php
  echo Html::a(Yii::t('diecoding', 'Delete'), ['delete', 'id' => $model->name], [
    'class'        => 'btn btn-danger',
    'data-confirm' => Yii::t('diecoding', 'Are you sure to delete this item?'),
    'data-method'  => 'post',
  ]);
  ?>
</p>

<div class="table-responsive">

  <?php
  echo DetailView::widget([
    'model' => $model,
    'attributes' => [
      'name',
      'className',
    ],
  ]);
  ?>

</div>
