<?php

class ProjectRelation extends CActiveRecord {

    public function tableName() {
        return '{{project_relation}}';
    }

    public function rules() {
        return array(
          array('pid, uid', 'required'),
          array('uid', 'numerical', 'integerOnly' => true),
          array('pid', 'length', 'max' => 11),
          array('id, pid, uid', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
          'id' => 'ID',
          'pid' => '项目',
          'uid' => '创建者',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('uid', $this->uid);

        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    /**
     * 初始化创建者与项目的关系
     */
    public static function initRl($pid,$uid){
        $attr=array(
          'pid'=>$pid,
          'uid'=>$uid,
        );
        $m=new ProjectRelation;
        $m->attributes=$attr;
        $m->save();
    }

}
