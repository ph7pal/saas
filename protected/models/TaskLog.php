<?php

class TaskLog extends CActiveRecord {

    public function tableName() {
        return '{{task_log}}';
    }

    public function rules() {
        return array(
          array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
          array('pid, tid, uid, type,cTime', 'required'),
          array('status', 'default', 'setOnEmpty' => true, 'value' => Tasks::STATUS_PASSED),
          array('status', 'numerical', 'integerOnly' => true),
          array('pid, tid , uid , touid , cTime,rid', 'length', 'max' => 11),
          array('content', 'length', 'max' => 255),
          array('type', 'length', 'max' => 16),
          array('id, pid, tid, uid, content, type, status, cTime', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
          'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
          'ownerInfo' => array(self::BELONGS_TO, 'Users', 'touid'),
          'projectInfo' => array(self::BELONGS_TO, 'Projects', 'pid'),
        );
    }

    public function attributeLabels() {
        return array(
          'id' => 'ID',
          'pid' => 'Pid',
          'tid' => '所属对象',
          'uid' => 'Uid',
          'touid' => 'touid',
          'rid' => '对应关系的对象ID',
          'content' => '评论内容',
          'type' => '分类',
          'status' => 'Status',
          'cTime' => 'C Time',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('tid', $this->tid, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('touid', $this->touid, true);
        $criteria->compare('rid', $this->rid, true);//related id
        $criteria->compare('content', $this->content, true);
        $criteria->compare('type', $this->type, true);

        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function exTypes($type,$return='') {
        $attr = array(
          '1' => array('type' => 'create', 'desc' => '创建任务'),
          '2' => array('type' => 'del_task', 'desc' => '删除任务'),
          '3' => array('type' => 'complete_task', 'desc' => '完成任务'),
          '4' => array('type' => 'edit_title', 'desc' => '编辑标题'),
          '5' => array('type' => 'edit_desc', 'desc' => '编辑描述'),
          '6' => array('type' => 'add_attach', 'desc' => '添加附件'),
          '7' => array('type' => 'del_attach', 'desc' => '删除附件'),
          '8' => array('type' => 'edit_tags', 'desc' => '添加标签'),
          '9' => array('type' => 'del_tag', 'desc' => '删除标签'),
          '10' => array('type' => 'add_duedate', 'desc' => '指派结束时间'),
          '11' => array('type' => 'del_duedate', 'desc' => '取消结束时间'),
          '12' => array('type' => 'change_duedate', 'desc' => '更改结束时间'),
          '13' => array('type' => 'assign_task', 'desc' => '指派任务'),
          '14' => array('type' => 'del_task_assign', 'desc' => '取消指派任务'),
          '50' => array('type' => 'subtask', 'desc' => '创建子任务'),
          '51' => array('type' => 'del_subtask', 'desc' => '删除子任务'),
          '52' => array('type' => 'edit_subtask', 'desc' => '编辑子任务'),
          '53' => array('type' => 'complete_subtask', 'desc' => '完成子任务'),
          '54' => array('type' => 'assign_subtask', 'desc' => '指派子任务'),
          '55' => array('type' => 'del_subtask_assign', 'desc' => '取消指派任务'),
          '56' => array('type' => 'add_subtask_duedate', 'desc' => '指派子任务结束时间'),
          '57' => array('type' => 'del_subtask_duedate', 'desc' => '取消子任务结束时间'),
          '58' => array('type' => 'change_subtask_duedate', 'desc' => '修改子任务结束时间'),
          '100' => array('type' => 'favorite', 'desc' => '收藏任务'),
          '101' => array('type' => 'cancel_favorite', 'desc' => '取消收藏'),
          '102' => array('type' => 'comment', 'desc' => '评论'),
        );
        $_attr=array();
        if(is_numeric($type)){
            $_attr=$attr[$type];
            $_attr['key']=$type;
        }else{
            foreach($attr as $k=>$v){
                if($v['type']==$type){
                    $_attr=$v;
                    $_attr['key']=$k;
                    break;
                }
            }            
        }
        if($return=='type'){
            return $_attr['type'];
        }elseif($return=='desc'){
            return $_attr['desc'];
        }elseif($return=='key'){
            return $_attr['key'];
        }
        return $_attr;
    }
    
    public static function add($attr){
        if(empty($attr)){
            return false;
        }
        $tl=new TaskLog;
        $tl->attributes=$attr;
        $tl->save();
    }

}
