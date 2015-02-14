<?php

class H extends CController {

    public $layout = 'admin';
    protected $_noColButOther;
    public $listTableTitle; //当前查看列表的名称

    public function init() {
        Yii::app()->setTimeZone('Asia/Chongqing');
        zmf::checkApp();
        $this->checkPower('login');
    }

    public function checkPower($type, $json = false, $return = false) {
        T::checkPower($type, $json, $return, TRUE);
    }

    static public function byteFormat($size, $dec = 2) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos ++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

    static public function jsonOutPut($status = 0, $msg = '', $end = true, $return = false) {
        T::jsonOutPut($status, $msg, $end, $return);
    }

    static public function message($action = 1, $content = '', $redirect = 'javascript:history.back(-1);', $timeout = 3) {
        T::message($action, $content, $redirect, $timeout);
    }

}
