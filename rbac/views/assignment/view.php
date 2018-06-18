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


use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;


/**
 * @var $this yii\web\View
 * @var $model diecoding\rbac\models\Assignment
 * @var $fullnameField string
 */

$userName = $model->$usernameField;
if (!empty($fullnameField)) {
  $displayName  = ArrayHelper::getValue($model, $fullnameField);
  $userName    .= " ({$displayName})";
}

$userName    = Html::encode($userName);
$this->title = Yii::t('diecoding', 'Assignment') . " : {$userName}";

$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

$success = $model->getItems();
unset($success['available']['Developer']);
unset($success['available']['Peserta']);


$items = Json::htmlEncode([
  'items' => $success,
]);

$this->registerJs($this->render('_script', ['items' => $items]));

$loading = '<i class  ="glyphicon glyphicon-refresh icon-refresh-animate"></i>';
$btn     = ['<i class ="glyphicon icon-success refresh"></i>', '<i class="glyphicon icon-danger refresh"></i>'];

?>

<h1><?=$this->title;?></h1>

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

      <?= Html::a("{$btn[0]}{$loading}", ['assign', 'id' => (string) $model->id], [
        'class'       => 'btn btn-success btn-lg btn-assign',
        'data-target' => 'available',
        'title'       => Yii::t('diecoding', 'Assign'),
      ]
      ) ?>
      <?= Html::a("{$btn[1]}{$loading}", ['revoke', 'id' => (string) $model->id], [
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
