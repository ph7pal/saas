<?php

class Comments extends CActiveRecord {

    public function tableName() {
        return '{{comments}}';
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
          array('uid, pid, logid, content, classify', 'required'),
          array('status', 'numerical', 'integerOnly' => true),
          array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
          array('uid, tocommentid, pid, logid, cTime', 'length', 'max' => 11),
          array('content', 'length', 'max' => 255),
          array('classify', 'length', 'max' => 16),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, uid, tocommentid, pid, logid, content, classify, status, cTime', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
          'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    public function attributeLabels() {
        return array(
          'id' => 'ID',
          'uid' => 'Uid',
          'tocommentid' => '回复评论ID',
          'pid' => 'Pid',
          'logid' => '所属对象',
          'content' => '评论内容',
          'classify' => '分类',
          'status' => 'Status',
          'cTime' => 'C Time',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('classify', $this->classify, true);

        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
