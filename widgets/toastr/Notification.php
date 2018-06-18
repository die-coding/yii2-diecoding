<?php
# @Author: Die Coding | www.diecoding.com
# @Date:   23 January 2018
# @Email:  diecoding@gmail.com
# @Last modified by:   Die Coding | www.diecoding.com
# @Last modified time: 24 January 2018
# @License: MIT
# @Copyright: 2018



namespace diecoding\widgets\toastr;

class Notification extends NotificationBase
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (in_array($this->type, $this->types)){
            return $this->view->registerJs("toastr.{$this->type}(\"{$this->message}\", \"{$this->title}\", {$this->options});");
        }

        return $this->view->registerJs("toastr.{$this->typeDefault}(\"{$this->message}\", \"{$this->title}\", {$this->options});");
    }
}
