<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/

namespace diecoding\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class ListMenu extends Component
{
  public function main()
  {
    $items = [
      // ['label' => 'Beranda', 'url' => ['/']],
      // [
      //   'label' => 'Fitur',
      //   'visible' => Yii::$app->assign->isPeserta(),
      //   'items' => [
      //     ['label' => 'Cetak Kartu Peserta', 'url' => ['/profil/cetak-kartu']],
      //     ['label' => 'Download Materi Belajar', 'url' => ['/profil/download']],
      //     ['label' => 'Ubah Kata Sandi', 'url' => ['/profil/password']],
      //     ['label' => 'Ubah Foto', 'url' => ['/profil/photo']],
      //     ['label' => 'Data Jumlah Pendaftar', 'url' => ['/profil/data-jumlah-pendaftar']],
      //   ],
      // ],
      // [
      //   'label' => 'Informasi',
      //   'items' => [
      //     ['label' => 'Informasi Umum', 'url' => ['/site/informasi']],
      //     ['label' => 'Cara Daftar', 'url' => ['/site/tata-cara-pendaftaran']],
      //     ['label' => 'Jadwal Penting', 'url' => ['/site#jadwal-penting']],
      //   ],
      // ],
      // ['label' => 'Lokasi', 'url' => ['/site#panlok']],
      // [
      //   'label' => 'Download',
      //   'items' => [
      //     ['label' => 'Materi Belajar', 'url' => ['/site/download-materi']],
      //     ['label' => 'Berkas Aritmatika', 'url' => ['/site/download']],
      //   ],
      // ],
      // ['label' => 'Pengumuman', 'url' => ['/site/berita']],
      // [
      //   'label' => 'Tentang',
      //   'items' => [
      //     ['label' => 'Galeri Tahun Lalu', 'url' => ['/site/galeri']],
      //     ['label' => 'Kontak Kami', 'url' => ['/site/kontak']],
      //   ],
      // ],
    ];


    if (Yii::$app->option->isFrontend) {
      return \diecoding\rbac\components\MenuHelper::getList();
    }

    return ArrayHelper::merge(
      $items,
      \diecoding\rbac\components\MenuHelper::getList(),
      $this->hakAkses()
    );
  }

  public function hakAkses()
  {
    $a = Yii::$app->assign;

    if (Yii::$app->user->isGuest || $a->isAssign("Peserta") || $a->isGuest()) {
      return [];
    }

    $items = [];
    foreach ($a->getAllAssign() as $i => $_a) {
      $s           = null;
      $url         = ["/option/set-assign"];
      $linkOptions = [
        "data" => [
          "method" => "post",
          "params" => [
            "assign"   => Yii::$app->encrypter->encrypt($_a),
          ]
        ]
      ];

      if ($a->getAssign() === $_a) {
        $s   = "active";
        $url = "#";
        $linkOptions = [];
      }

      $items[$i] = [
        "label" => $_a,
        "url"   => $url,
        "options" => [
          "class" => $s,
        ],
        "linkOptions" => $linkOptions,
      ];
    }

    $menu[] = [
      "label"   => "Peran",
      "icon"    => "users",
      "url"     => "javascript:void(0);",
      "items"   => $items,
    ];

    return $menu;
  }
}
