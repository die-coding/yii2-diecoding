Yii2 RBAC
=========
RBAC / Auth Manager for Yii 2


Cara Memasang
-------------

Sangat disarankan memasangnya menggunakan [composer](http://getcomposer.org/download/).

Jalankan pada console:

```
composer require --prefer-dist diecoding/yii2-rbac "@dev"
```

atau tambahkan:

```
"diecoding/yii2-rbac": "@dev"
```

pada baris `require` yang terdapat di berkas `composer.json`. Kemudian jalankan

```
composer update
```

pada console


Usage
-----

Use menu

```console
php yii migrate --migrationPath=@diecoding/rbac/migrations
```
