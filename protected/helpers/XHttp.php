<?php

class XHttp {

    /**
     * 文件下载
     */
    static function download($filename, $showname = '', $content = '', $expire = 180) {
        Yii::import('application.vendors.*');
        require_once 'Tp/Http.class.php';
        Http::download($filename, $showname, $content, $expire);
    }

}

