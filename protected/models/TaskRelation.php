<?php

class TaskRelation extends CActiveRecord {

    public function tableName() {
        return '{{task_relation}}';
    }

    public function rules() {
        return array(
          array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
          array('pid, tid, uid', 'required'),
          array('uid, touid', 'numerical', 'integerOnly' => true),
          array('pid, tid, cTime', 'length', 'max' => 11),
          array('id, pid, tid, uid, touid, cTime', 'safe', 'on' => 'search'),
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
          'tid' => '任务',
          'uid' => '创建者',
          'touid' => '被指派人',
          'cTime' => '创建时间',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('tid', $this->tid, true);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('touid', $this->touid);
        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function add($attr){
        if(empty($attr)){
            return false;
        }
        $model=new TaskRelation;
        $model->attributes=$attr;
        $model->save();
    }

}
