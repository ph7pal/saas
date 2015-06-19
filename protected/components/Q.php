<?php

/**
 * 前台共用类
 */
class Q extends T {

    public $layout = 'main';
    public $referer;
    public $menu = array();
    public $breadcrumbs = array();
    public $userInfo=array();
    public $uid;
    
    public $projectid;
    public $taskid;
    
    public $projects=array();
    public $members=array();
    public $groupid;
    public $groupInfo;

    function init() {
        parent::init();
        Yii::app()->theme = 'web';
        $uid=zmf::uid();
        if($uid){            
            $this->userInfo=  Users::getInfo($uid);
            $this->uid=$uid;
        }
        $groupid=zmf::filterInput($_GET['groupid']);
        if($groupid){
            $this->groupid=$groupid;
            $this->groupInfo=  Group::findOne($groupid);
        }
//        self::_referer();
    }

    function _referer() {
        $currentUrl = Yii::app()->request->url;
        $arr = array(
            '/site/',
            '/error/',
            '/attachments/',
            '/weibo/',
            '/qq/',
            '/weixin/',
        );
        $set = true;
//        if (Posts::checkImg($currentUrl)) {
//            $set = false;
//        }
        if ($set) {
            foreach ($arr as $val) {
                if (!$set) {
                    break;
                }
                if (strpos($currentUrl, $val) !== false) {
                    $set = false;
                    break;
                }
            }
        }
        if ($set && Yii::app()->request->isAjaxRequest) {
            $set = false;
        }
        $referer = zmf::getCookie('refererUrl');
        if ($set) {
            zmf::setCookie('refererUrl', $currentUrl, 86400);
        }
        if ($referer != '') {
            $this->referer = $referer;
        }
    }

}
