<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 08 March 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 10 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\Menu */

$this->title = Yii::t('diecoding', 'Update Menu') . ': ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('diecoding', 'Update');
?>

<h1><?= Html::encode($this->title) ?></h1>

<?=
$this->render('_form', [
  'model' => $model,
]);
?>
