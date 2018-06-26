<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */


namespace diecoding\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\models\Notification as Notif;
use common\models\Peserta;

class Notification extends Component
{
  const SUDAH_DIBACA = 'SUDAH DIBACA';
  const BELUM_DIBACA = 'BELUM DIBACA';

  public function setOn($value, $route, $user_id = null)
  {
    if ($user_id == 'admin') {
      $access = new Access();
      $admin = $access->getAllUser();

      foreach ($admin as $adm) {
        $model = new Notif();
        $modelOn = $model->getNotification($adm->user_id, $route, $value);
        if ($modelOn !== null) {
          return false;
        }

        $model = new Notif();
        $model->setValue($value);
        $model->setRoute($route);
        $model->setUserId($adm->user_id);
        $model->setStatus(self::BELUM_DIBACA);
        $model->created();
        $model->save();
      }
    } else {
      if ($user_id == null) {
        $user_id = Yii::$app->user->identity->id;
      }

      $model = new Notif();
      $modelOn = $model->getNotification($user_id, $route, $value);
      if ($modelOn !== null) {
        return false;
      }

      $model = new Notif();
      $model->setValue($value);
      $model->setRoute($route);
      $model->setUserId($user_id);
      $model->setStatus(self::BELUM_DIBACA);
      $model->created();
      $model->save();
    }
  }

  public function setOff($route, $user_id = null)
  {
    if ($user_id == 'admin') {
      $admin = Access::getAllUser();

      foreach ($admin as $adm) {
        $model = new Notif();
        $modelOff = $model->getNotification($adm->user_id, $route);

        if ($modelOff == null) {
          return false;
        }
        $model->setStatus(self::SUDAH_DIBACA);
        $model->save();
      }
    } else {
      if ($user_id == null) {
        $user_id = Yii::$app->user->identity->id;
      }

      $model = new Notif();
      $modelOff = $model->getNotification($user_id, $route);

      if ($modelOff == null) {
        return false;
      }
      $model->setStatus(self::SUDAH_DIBACA);
      $model->save();
    }
  }

  public function count($status = self::BELUM_DIBACA)
  {
    $user_id = Yii::$app->user->identity->id;

    $count = Notif::find()->where(['user_id' => $user_id, 'status' => $status])->count();
    return $count;
  }

  public function foreach($limit = null, $status = self::BELUM_DIBACA)
  {
    $user_id = Yii::$app->user->identity->id;

    if ($limit == null) {
      $model = Notif::find()->where(['user_id' => $user_id, 'status' => $status])->orderby('created_at DESC')->all();
    } else {
      $model = Notif::find()->where(['user_id' => $user_id, 'status' => $status])->orderby('created_at DESC')->limit($limit)->all();
    }
    return $model;
  }

  public function getUserNotification()
  {
    $model = new Peserta();
    $out   = [];

    if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->photo) {
        $out[] = [
          'alert'   => 'danger',
          'content' => "Maaf, mohon segera perbarui <strong>Photo Profil</strong> Anda.",
        ];
    }

    if (strtotime(Yii::$app->option->extends('a_ph_tanggal', 'aritmatika')) <= strtotime(date('Y/m/d')) && Yii::$app->option->extends('a_ph_status', 'aritmatika') == 1 && Yii::$app->option->extends('a_ph_visible_main', 'aritmatika') == 1) {
      $h = $model->getDataHasil(Yii::$app->user->id);
      if ($h[$model::PENYISIHAN] === $model::LOLOS) {

        $alert   = 'success';
        $content = "Selamat Anda <strong>Lolos</strong> Babak Penyisihan";
        if ($h[$model::SEMI_FINAL] === $model::LOLOS) {
          $content  = "Selamat Anda <strong>Lolos</strong> Babak Semi Final";
        } elseif ($h[$model::SEMI_FINAL] === $model::TIDAK_LOLOS) {
          $alert   = 'danger';
          $content = "Maaf Anda <strong>Tidak Lolos</strong> Babak Semi Final";
        }
        $out[] = [
          'alert'   => $alert,
          'content' => $content,
        ];
      } elseif ($h[$model::PENYISIHAN] === $model::TIDAK_LOLOS) {
        $out[] = [
          'alert'   => 'danger',
          'content' => "Maaf Anda <strong>Tidak Lolos</strong> Babak Penyisihan.",
        ];
      }
    }

    $status = Yii::$app->apiRequest->getStatusPeserta();
    if ($status === $model::BELUM_DIVALIDASI) {
      $out[] = [
        'alert'   => 'info',
        'content' => "Maaf, Anda belum terdaftar menjadi peserta Aritmatika. Mohon segera lakukan pembayaan sebelum pendaftaran ditutup. Info pembayaran " . Html::a('di sini', ['/site/laman', 'p' => 'Konfirmasi Bayar'], ['class' => 'alert-link']),
      ];
    } elseif ($status === $model::DITOLAK) {
      $out[] = [
        'alert'   => 'danger',
        'content' => "Maaf, Pendaftaran Anda Ditolak",
      ];
    }

    return $out;
  }
}
