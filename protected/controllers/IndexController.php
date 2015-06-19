<?php

class IndexController extends Q {

    public $layout = 'main';
   
    public function actionWelcome(){
        if(!$this->uid){
            $this->redirect(array('site/login'));
        }
        $this->layout='simple';
        $sql="SELECT g.* FROM {{group}} g,{{group_link}} gl WHERE g.id=gl.groupid AND gl.uid='{$this->uid}'";
        $groups=  Yii::app()->db->createCommand($sql)->queryAll();
        $data=array(
            'groups'=>$groups
        );
        $this->pageTitle='团队列表';
        $this->render('welcome',$data);
    }

    public function actionIndex() {
        $groupid=zmf::filterInput($_GET['groupid']);
        if(!$groupid){
            $this->redirect(array('index/welcome'));
        }
        $uid=$this->uid;
        $this->projects=  Projects::getGroupAll($groupid);
        $this->members=Users::getMembers($groupid);
        $data=array(
          'projects'=>$projects
        );
        $this->pageTitle=  $this->groupInfo['title'];
        $this->render('index',$data);
    }

}
