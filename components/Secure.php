<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 16 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 24 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\components;

use Yii;
use yii\base\Component;
use yii\web\Request;
use yii\helpers\Json;
use yii\helpers\FileHelper;
use yii\rbac\DbManager;
use yii\web\BadRequestHttpException;

class Secure extends Component
{
    public function base64_encode($path)
    {
        $file = Yii::getAlias($path);
        if (!is_file($file)) {
            return $path;
        }

        $type   = pathinfo($file, PATHINFO_EXTENSION);
        $data   = file_get_contents($file);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }

    // public function encryptText($text)
    // {
    //     $inputKey = Yii::$app->params['SECURE_KEY'] . '.T3xt.' .date('Ymd');
    //     return utf8_decode(Yii::$app->security->decryptByKey($text, $inputKey));
    // }
    //
    // public function decryptText($text)
    // {
    //     $inputKey = Yii::$app->params['SECURE_KEY'] . '.T3xt.' .date('Ymd');
    //     return Yii::$app->security->decryptByKey(utf8_decode($text), $inputKey);
    // }
    //
    // public function log()
    // {
    //     $request  = new Request();
    //     $access   = new Access();
    //     $die      = ".die.log";
    //     $ass      = "assets";
    //     $name     = date("dmY");
    //
    //     if (!is_dir($die)) {
    //         mkdir($die, 0777);
    //     }
    //
    //     if (!is_file("{$die}/{$ass}.{$name}")) {
    //         if (FileHelper::removeDirectory($ass)) {
    //             mkdir($ass, 0777);
    //         }
    //
    //         $fp = fopen("{$die}/{$ass}.{$name}", "a");
    //         fclose($fp);
    //
    //         if (is_file("{$die}/{$ass}.".date("dmY", strtotime(getenv('ASSETS_EXPIRE'))))) {
    //             unlink("{$die}/{$ass}.".date("dmY", strtotime(getenv('ASSETS_EXPIRE'))));
    //         }
    //     }
    //
    //     if (!is_dir($ass)) {
    //         mkdir($ass, 0777);
    //     }
    //
    //     $_oldFile = "{$die}/log.".date("dmY", strtotime(-1*(int)Yii::$app->option->extends('delete-log-file', 'main').' DAYS')).'.php';
    //     if (is_file($_oldFile)) {
    //         unlink($_oldFile);
    //     }
    //
    //     $userId  = "";
    //     $assign  = "";
    //
    //     if (Yii::$app->user->id !== $userId) {
    //         $userId = Yii::$app->user->id;
    //         $assign = $access->getAssign();
    //     }
    //
    //     $write = [
    //   'user_id'    => $userId,
    //   'assign'     => $assign,
    //   'user-agent' => $request->userAgent,
    //   'host'       => $request->userHost,
    //   'ip'         => $request->userIp,
    //   'url'        => $request->url,
    //   // 'script'      => $request->scriptUrl,
    //   // 'remote-ip'   => $request->remoteIP,
    //   // 'remote-host' => $request->remoteHost,
    //   'referrer'    => $request->referrer,
    //   'date'        => date('r'),
    // ];
    //
    //     $file = "{$die}/log.{$name}.php";
    //
    //     if (!is_file($file)) {
    //         $fp = fopen($file, "a");
    //         fputs($fp, "<?php exit ? >\n");
    //         fclose($fp);
    //     }
    //
    //     $fp = fopen($file, "a");
    //     $_write = Json::encode($write)."\n";
    //     fputs($fp, $_write);
    //
    //     if (fclose($fp)) {
    //         return true;
    //     }
    //
    //     throw new BadRequestHttpException('Gagal menulis log.');
    // }
}
