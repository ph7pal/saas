<?php

class IndexController extends Q {

    public $layout = 'main';
   
    public function actionWelcome(){
        if(!$this->uid){
            $this->redirect(array('site/login'));
        }
        $this->layout='simple';
        $sql="SELECT g.id,g.title,g.avatar FROM {{group}} g,{{group_link}} gl WHERE g.id=gl.groupid AND gl.uid='{$this->uid}'";
        $groups=  Yii::app()->db->createCommand($sql)->queryAll();
        foreach($groups as $k=>$v){
            $groups[$k]['id']=  tools::jiaMi($v['id']);
        }
        $data=array(
            'groups'=>$groups
        );
        $this->pageTitle='团队列表';
        $this->render('welcome',$data);
    }

    public function actionIndex() {
        if(!$this->uid){
            $this->redirect(array('site/login'));
        }
        if(!$this->groupid){
            $this->redirect(array('index/welcome'));
        }
        $uid=$this->uid;
        $this->projects=  Projects::getGroupAll($this->groupid);
        $this->members=Users::getMembers($this->groupid);
        $this->pageTitle=  $this->groupInfo['title'];
        $this->render('index',$data);
    }

}
