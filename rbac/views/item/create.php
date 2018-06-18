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

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\AuthItem */
/* @var $context diecoding\rbac\components\ItemController */

$context = $this->context;
$labels  = $context->labels();

$this->title = Yii::t('diecoding', 'Create {value}', ['value' => $labels['Item']]);
$this->params['breadcrumbs'][] = ['label' => $labels['Items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<?=
$this->render('_form', [
  'model' => $model,
]);
?>
