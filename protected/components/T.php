<?php

class T extends CController {
    protected $platform;
    protected $zmf = false;
    public $pageDescription;
    public $keywords;    
    //模板有关，均已theme开头
    public $isMobile=false;
    public $code;//请求连接中的code   

    public function init() {
        if (!zmf::config('closeSite')) {
            self::_closed();
        }
        $code=zmf::filterInput(Yii::app()->request->getParam('code'),'t',1);
        if($code){
            $code=tools::jieMi($code);
        }
        
    }

    static public function jsonOutPut($status = 0, $msg = '', $end = true, $return = false) {
        $outPutData = array(
            'status' => $status,
            'msg' => $msg
        );
        $json = CJSON::encode($outPutData);
        if ($return) {
            return $json;
        } else {
            echo $json;
        }
        if ($end) {
            Yii::app()->end();
        }
    }

    public function _closed($reason = '') {
        if ($reason == '') {
            $reason = zmf::config('closeSiteReason');
        }
        $this->renderPartial('/error/close', array('message' => $reason));
        Yii::app()->end();
    }
    

}
