<?php

/**
 * This is the model class for table "{{group_link}}".
 *
 * The followings are the available columns in table '{{group_link}}':
 * @property integer $id
 * @property string $groupid
 * @property string $uid
 * @property integer $isAdmin
 */
class GroupLink extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group_link}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('groupid, uid', 'required'),
            array('isAdmin', 'numerical', 'integerOnly' => true),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid,cTime', 'length', 'max' => 10),
            array('groupid', 'length', 'max' => 19),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('groupid, uid, isAdmin', 'safe', 'on' => 'search'),
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
            'id' => '团队关系ID',
            'groupid' => '团队ID',
            'uid' => '用户ID',
            'isAdmin' => '是否管理员',
            'cTime' => '加入时间',
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
        $criteria->compare('groupid', $this->groupid, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('isAdmin', $this->isAdmin);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GroupLink the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    /**
     * 简单创建数据
     * @param type $attr
     * @return type
     */
    public static function add($attr){
        $model=new GroupLink;
        $model->attributes=$attr;
        return $model->save();
    }
    /**
     * 查找某人是否在团队中
     */
    public static function findRelation($uid,$gid){
        if(!$uid || !$gid){
            return false;
        }
        $info=  GroupLink::model()->find('uid=:uid AND groupid=:gid', array(':uid'=>$uid,':gid'=>$gid));
        if(!$info){
            return false;
        }
        return $info;
    }

}
