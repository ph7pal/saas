<?php

class Users extends CActiveRecord {
    
    public $noticeNum;

    public function tableName() {
        return '{{users}}';
    }

    public function rules() {
        return array(
            array('username, password, email, groupid, email_status, status, hash', 'required'),
            array('groupid, email_status, status', 'numerical', 'integerOnly' => true),
            array('username, password, email', 'length', 'max' => 255),
            array('hash', 'length', 'max' => 6),
            array('id, username, password, email, groupid, email_status, status, hash', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
            'groupid' => '是否创建过团队',
            'email_status' => '邮箱状态',
            'status' => 'Status',
            'hash' => 'Hash',
            'noticeNum' => '提醒数量',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getInfo($uid) {
        if (!$uid) {
            return false;
        }
        $info = Users::model()->findByPk($uid);
        unset($info['password']);
        unset($info['hash']);
        return $info;
    }

    /**
     * 获取团队下的所有成员
     * @param type $groupid
     * @return boolean
     */
    public static function getMembers($groupid, $type = 'all') {
        if (!$groupid) {
            return false;
        }
        if ($type == 'all') {
            $sql = "SELECT u.id,u.username,u.status FROM {{users}} u,{{group_link}} gl WHERE u.id=gl.uid AND gl.groupid=:groupid";
        } elseif ($type == 'passed') {
            $sql = "SELECT u.id,u.username,u.status FROM {{users}} u,{{group_link}} gl WHERE u.id=gl.uid AND gl.groupid=:groupid AND u.status=" . Tasks::STATUS_PASSED;
        } else {
            return array();
        }
        $users = Users::model()->findAllBySql($sql, array(':groupid' => $groupid));
        return $users;
    }

}
