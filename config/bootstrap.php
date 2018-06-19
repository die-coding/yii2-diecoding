<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

Yii::setAlias('@themes', dirname(dirname(__DIR__)) . '/yii2-themes');
Yii::setAlias('@diecoding', dirname(__DIR__));

// getenv()
$dotenv = new Dotenv\Dotenv(__DIR__);
if (file_exists(__DIR__ . '/.env')) {
  $dotenv->load();
}
