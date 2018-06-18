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
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this  yii\web\View
 * @var $model diecoding\rbac\models\BizRule
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel diecoding\rbac\models\searchs\BizRule
 */

$this->title = Yii::t('diecoding', 'Rules');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<p>
  <?= Html::a(Yii::t('diecoding', 'Create Rule'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class="table-responsive">

  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      [
        'attribute' => 'name',
        'label' => Yii::t('diecoding', 'Name'),
      ],
      ['class' => 'yii\grid\ActionColumn',],
    ],
  ]);
  ?>

</div>
