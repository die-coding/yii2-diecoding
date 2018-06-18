<?php
/**
 * @Author: Die Coding <www.diecoding.com>
 * @Date:   19 February 2018
 * @Email:  diecoding@gmail.com
 * @Last modified by:   Die Coding <www.diecoding.com>
 * @Last modified time: 10 March 2018
 * @License: MIT
 * @Copyright: 2018
 */


 use yii\helpers\Html;
 use yii\bootstrap\Nav;
 use yii\bootstrap\NavBar;
 use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

diecoding\rbac\MainAsset::register($this);

$module = $this->context->module;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>
<body>
  <?php $this->beginBody() ?>
  <div class="wrap">
    <?php
    NavBar::begin([
      'brandLabel' => false,
      'options'    => [
        'class' => 'navbar-inverse navbar-fixed-top'
      ],
    ]);

    if (!empty($this->params['top-menu']) && isset($this->params['nav-items'])) {
      echo Nav::widget([
        'options' => [
          'class' => 'nav navbar-nav',
        ],
        'items' => $this->params['nav-items'],
      ]);
    }

    echo Nav::widget([
      'options' => [
        'class' => 'nav navbar-nav navbar-right',
      ],
      'items' => $module->navbar,
    ]);
    NavBar::end();
    ?>

    <div class="container">
      <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
      ]
      ) ?>

      <?= $content ?>
    </div>
  </div>

  <footer class="footer">
    <div class="container">

      <p class="pull-left"><?= $module->footer['left'] ?></p>

      <p class="pull-right"><?= $module->footer['right'] ?></p>
    </div>
  </footer>

  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
