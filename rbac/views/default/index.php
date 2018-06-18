<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 20 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 11 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


use yii\web\View;
use yii\helpers\Markdown;
use yii\helpers\Url;

/**
 * @var $this View
 */

if (($pos = strrpos($page, '/')) === false) {
  $baseDir = '';
  $this->title = substr($page, 0, strrpos($page, '.'));
} else {
  $baseDir = substr($page, 0, $pos) . '/';
  $this->title = substr($page, $pos + 1, strrpos($page, '.') - $pos - 1);
}

if ($page == 'README.md') {
  $this->params['breadcrumbs'][] = 'Readme';
  $menus = $this->context->module->getMenus();
  $links = [];
  foreach ($menus as $menu) {
    $url = Url::to($menu['url'], true);
    $links[] = "[**{$menu['label']}**]({$url})";
  }
  $body = str_replace(':smile:.', ".\n\n" . implode('  ', $links) . "\n", file_get_contents(Url::to('@diecoding/rbac/README.md')));
} else {
  $body = file_get_contents(Url::to("@diecoding/rbac/{$page}"));
}

$body = preg_replace_callback('/\]\((.*?)\)/', function($matches) use($baseDir) {
  $link = $matches[1];
  if (strpos($link, '://') === false) {
    if ($link[0] == '/') {
      $link = Url::current(['page' => ltrim($link, '/')], true);
    } else {
      $link = Url::current(['page' => $baseDir . $link], true);
    }
  }
  return "]($link)";
}, $body);

echo Markdown::process($body, 'gfm');
