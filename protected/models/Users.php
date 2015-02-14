<?php

class Users extends CActiveRecord {

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
          'groupid' => '用户组',
          'email_status' => '邮箱状态',
          'status' => 'Status',
          'hash' => 'Hash',
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
    
    public static function getInfo($uid){
        if(!$uid){
            return false;
        }
        $info=  Users::model()->findByPk($uid);
        unset($info['password']);
        unset($info['hash']);
        return $info;
    }
    
    public static function getMembers($uid){
        if(!$uid){
            return false;
        }
        $sql="SELECT id,username,status FROM {{users}} WHERE id IN(SELECT uid FROM {{project_relation}} WHERE pid IN(SELECT pid FROM {{project_relation}} WHERE uid=:uid))";
        $users=Users::model()->findAllBySql($sql, array(':uid'=>$uid));
        return $users;
    }

}
