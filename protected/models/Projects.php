<?php

class Projects extends CActiveRecord {

    public function tableName() {
        return '{{projects}}';
    }

    public function rules() {
        return array(
            array('uid, title', 'required'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Tasks::STATUS_PASSED),
            array('status', 'numerical', 'integerOnly' => true),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, groupid,expired_time,cTime', 'length', 'max' => 10),
            array('title, desc', 'length', 'max' => 255),
            array('id, uid, title, desc, cTime , expired_time, status', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'groupid' => '所属团队',
            'uid' => '创建者',
            'title' => '项目名',
            'desc' => '项目描述',
            'expired_time' => '过期时间',
            'cTime' => '创建时间',
            'status' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 获取用户所有项目
     * @param type $uid
     * @return boolean
     */
    public static function getUsersAll($uid) {
        if (!$uid) {
            return false;
        }
        $items = Projects::model()->findAllBySql('SELECT * FROM {{projects}} WHERE id IN(SELECT pid FROM {{project_relation}} WHERE uid=:uid)', array(':uid' => $uid));
        return $items;
    }

    /**
     * 获取团队下的所有项目
     * @param type $groupid
     * @return boolean]
     */
    public static function getGroupAll($groupid) {
        if (!$groupid) {
            return false;
        }
        $items = Projects::model()->findAll('groupid=:groupid AND status=' . Tasks::STATUS_PASSED, array(':groupid' => $groupid));
        return $items;
    }
    
    /**
     * 获取项目下的参与人
     * @param type $pid
     */
    public static function getProjectMembers($pid){
        $sql="SELECT u.id,u.username FROM {{users}} u,{{}}";
    }

}
