<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/

namespace diecoding\components;

use Yii;
use yii\base\Component;

/**
*
* @author Die Coding <diecoding@gmail.com>
* @since 0.0.0
*/
class Date extends Component
{

  private $_day   = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

  private $_month = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  /**
  * @inheritdoc
  */
  public function init()
  {
    parent::init();
    if (!isset(Yii::$app->i18n->translations['diecoding'])) {
      Yii::$app->i18n->translations['diecoding'] = [
        'class'          => 'yii\i18n\PhpMessageSource',
        'sourceLanguage' => 'en',
        'basePath'       => '@diecoding/i18n',
      ];
    }

    $this->_day =  [
      Yii::t('diecoding', 'Sunday'),
      Yii::t('diecoding', 'Monday'),
      Yii::t('diecoding', 'Tuesday'),
      Yii::t('diecoding', 'Wednesday'),
      Yii::t('diecoding', 'Thursday'),
      Yii::t('diecoding', 'Friday'),
      Yii::t('diecoding', 'Saturday'),
    ];

    $this->_month = [
      '',
      Yii::t('diecoding', 'January'),
      Yii::t('diecoding', 'February'),
      Yii::t('diecoding', 'March'),
      Yii::t('diecoding', 'April'),
      Yii::t('diecoding', 'May'),
      Yii::t('diecoding', 'June'),
      Yii::t('diecoding', 'July'),
      Yii::t('diecoding', 'August'),
      Yii::t('diecoding', 'September'),
      Yii::t('diecoding', 'October'),
      Yii::t('diecoding', 'November'),
      Yii::t('diecoding', 'December'),
    ];
  }

  /**
  * @inheritdoc
  */
  public function isToday($date)
  {
    $date  = $this->date($date);
    $today = date('dmY', $date);

    return $today === date('dmY');
  }

  /**
  * @inheritdoc
  */
  public function d($date = null)
  {
    $date = $this->date($date);
    $day  = date('w', $date);
    $d    = $this->_day;

    return $d[$day];
  }

  /**
  * @inheritdoc
  */
  public function m($date = null)
  {
    $date  = $this->date($date);
    $month = date('n', $date);
    $m     = $this->_month;

    return $m[$month];
  }

  /**
  * @inheritdoc
  */
  public function dmy($date = null)
  {
    $date = $this->date($date);
    $d    = date('d', $date);
    $m    = $this->m($date);
    $y    = date('Y', $date);
    $out  = "{$d} {$m} {$y}";

    return $out;
  }

  /**
  * @inheritdoc
  */
  public function ldmy($date = null)
  {
    $date = $this->date($date);
    $l    = $this->d($date);
    $d    = date('d', $date);
    $m    = $this->m($date);
    $y    = date('Y', $date);
    $out  = "{$l}, {$d} {$m} {$y}";

    return $out;
  }

  /**
  * @inheritdoc
  */
  public function ldmyhi($date = null)
  {
    $date = $this->date($date);
    $l    = $this->d($date);
    $d    = date('d', $date);
    $m    = $this->m($date);
    $y    = date('Y', $date);
    $h    = date('H', $date);
    $i    = date('i', $date);
    $out  = "{$l}, {$d} {$m} {$y} " . '-' . " {$h}:{$i}";

    return $out;
  }

  /**
  * @inheritdoc
  */
  public function ldmyhis($date = null)
  {
    $date = $this->date($date);
    $l    = $this->d($date);
    $d    = date('d', $date);
    $m    = $this->m($date);
    $y    = date('Y', $date);
    $h    = date('H', $date);
    $i    = date('i', $date);
    $s    = date('s', $date);
    $out = "{$l}, {$d} {$m} {$y} " . '-' . " {$h}:{$i}:{$s}";

    return $out;
  }

  /**
  * @inheritdoc
  */
  private function date($date)
  {
    if (!$date) {
      $date = date('r');
    }

    if (!strtotime($date)) {
      return $date;
    }

    return strtotime($date);
  }
}
