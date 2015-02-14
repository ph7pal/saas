<?php

class IndexController extends T {

    public $layout = 'main';
    
    public function actionIndex() {
        $uid=$this->uid;
        $this->projects=  Projects::getUsersAll($uid);
        $this->members=Users::getMembers($uid);
        $data=array(
          'projects'=>$projects
        );
        $this->render('index',$data);
    }

}
