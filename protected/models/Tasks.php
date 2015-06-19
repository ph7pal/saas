<?php

class Tasks extends CActiveRecord {
    const STATUS_UNEDITED = 0;//未编辑
    const STATUS_PASSED = 1;//在显示
    const STATUS_FINISHED = 2;//已完成
    const STATUS_DELED = 3;//已删除
    
    public $members;

    public function tableName() {
        return '{{tasks}}';
    }

    public function rules() {
        return array(          
          array('pid, uid , title', 'required'),
          array('followers, tags, attaches, order, status', 'numerical', 'integerOnly' => true),
          array('cTime', 'default', 'setOnEmpty' => true, 'value' => time()),
          array('pid, tid, uid, expired_time,cTime', 'length', 'max' => 11),
          array('title, desc,members', 'length', 'max' => 255),
          array('id, pid, tid, uid, followers, tags, attaches, title, desc, order, expired_time,cTime, status', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
          'id' => 'ID',
          'pid' => '项目ID',
          'tid' => '所属任务ID',
          'uid' => '创建者',
          'followers' => '是否有成员',
          'tags' => '是否有标签',
          'attaches' => '是否有附件',
          'title' => '任务标题',
          'desc' => '任务描述',
          'order' => '排序',
          'expired_time' => '过期时间',
          'cTime' => '创建时间',
          'status' => 'Status',
          'members' => '参与者',
        );
    }

    public function search() {

        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
