<?php

/**
 * This is the model class for table "{{user_info}}".
 *
 * The followings are the available columns in table '{{user_info}}':
 * @property string $uid
 * @property string $truename
 * @property string $desc
 * @property string $url
 * @property string $register_ip
 * @property string $last_login_ip
 * @property string $register_time
 * @property string $last_login_time
 * @property string $login_count
 */
class UserInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('truename, desc, url, register_ip, last_login_ip, register_time, last_login_time, login_count', 'required'),
			array('truename, desc, url', 'length', 'max'=>255),
			array('register_ip, last_login_ip', 'length', 'max'=>15),
			array('register_time, last_login_time, login_count', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, truename, desc, url, register_ip, last_login_ip, register_time, last_login_time, login_count', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uid' => 'Uid',
			'truename' => '显示名',
			'desc' => '个人描述',
			'url' => '个人主页',
			'register_ip' => '注册IP',
			'last_login_ip' => '最近登录IP',
			'register_time' => '注册时间',
			'last_login_time' => '最近登录',
			'login_count' => '登录次数',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('truename',$this->truename,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('register_ip',$this->register_ip,true);
		$criteria->compare('last_login_ip',$this->last_login_ip,true);
		$criteria->compare('register_time',$this->register_time,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('login_count',$this->login_count,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
