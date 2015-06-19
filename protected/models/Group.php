<?php

/**
 * This is the model class for table "{{group}}".
 * 用户创建的团队
 * The followings are the available columns in table '{{group}}':
 * @property string $id
 * @property string $uid
 * @property string $title
 * @property string $desc
 * @property integer $members
 * @property string $avatar
 * @property string $cTime
 * @property string $updateTime
 * @property integer $status
 */
class Group extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Tasks::STATUS_PASSED),
            array('uid, title', 'required'),
            array('members, status', 'numerical', 'integerOnly' => true),
            array('id', 'length', 'max' => 19),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, cTime, updateTime', 'length', 'max' => 10),
            array('title, desc, avatar', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, title, desc, members, avatar, cTime, updateTime, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '团队ID',
            'uid' => '创建人ID',
            'title' => '团队名称',
            'desc' => '团队介绍',
            'members' => '团队成员数',
            'avatar' => '团队头像',
            'cTime' => '创建时间',
            'updateTime' => 'Update Time',
            'status' => 'Status',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('members', $this->members);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('updateTime', $this->updateTime, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Group the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function findOne($id) {
        if (!$id) {
            return false;
        }
        return Group::model()->findByPk($id);
    }

}
