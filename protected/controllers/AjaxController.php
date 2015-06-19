<?php

class AjaxController extends Q {

    public function init() {
        parent::init();
        if (!Yii::app()->request->isAjaxRequest) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
    }

    private function checkLogin() {
        if (Yii::app()->user->isGuest) {
            $this->jsonOutPut(0, Yii::t('default', 'loginfirst'));
        }
    }
    
    public function actionTasks(){
        $id=zmf::filterInput($_POST['pid'],'t',1);        
        $id=tools::jieMi($id);
        $id=(int)$id;
        if(!$id){
            $this->jsonOutPut(0, '页面不存在');
        }
    }

}
