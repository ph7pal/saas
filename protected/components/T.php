<?php

class T extends CController {

    public $menu = array();
    public $breadcrumbs = array();
    protected $platform;
    protected $zmf = false;
    public $pageDescription;
    public $keywords;    
    public $userInfo;
    //模板有关，均已theme开头
    public $isMobile=false;
    
    
    public $uid;
    public $code;//请求连接中的code
    public $projectid;
    public $taskid;
    
    public $projects=array();
    public $members=array();
    

    public function init() {
        if (!zmf::config('closeSite')) {
            self::_closed();
        }
        $this->uid=1;
        $code=zmf::filterInput(Yii::app()->request->getParam('code'),'t',1);
        if($code){
            $code=tools::jieMi($code);
        }
        if($this->uid){
            $this->userInfo=  Users::getInfo($this->uid);
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
