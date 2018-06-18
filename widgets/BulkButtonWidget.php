<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

namespace diecoding\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class BulkButtonWidget extends Widget
{
    public $label = '<span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;With selected&nbsp;&nbsp;';

    public $buttons = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
			$content = $this->label;
			foreach ($this->buttons as $key => $value) {
				$content .= $value . '&nbsp;';
			}

			return $content;
    }
}
