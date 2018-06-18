<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 06 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


 /* @var $this \yii\web\View */
 /* @var $content string */

$controller = $this->context;
$menus      = $controller->module->menus;
$route      = $controller->route;

foreach ($menus as $i => $menu) {
  $menus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
}
$this->params['nav-items'] = $menus;
$this->params['top-menu']  = true;
?>
<?php $this->beginContent("@diecoding/rbac/views/layouts/main.php") ?>

<div class="row">
  <div class="col-sm-12">
    <?= $content ?>
  </div>
</div>

<?php $this->endContent(); ?>
