<?php

class SiteController extends T {

    //public $layout = 'login';

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'minLength' => '2', // 最少生成几个字符
                'maxLength' => '4', // 最多生成几个字符
                'height' => '30',
                'width' => '60'
            ),            
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->message(0, '您已登录，请勿重复操作');
        }
        $model = new LoginForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $id = Yii::app()->user->id;
                $ip = Yii::app()->request->userHostAddress;
                $info = Users::model()->findByPk($id);
                $info->last_login_ip = ip2long($ip);
                $info->last_login_time = time();
                $info->login_count+=1;
                $info->save();
                $this->redirect(Yii::app()->createUrl('user/index'));
            }
        }
        $this->_noColButOther = 'login';
        $this->render('login', array('model' => $model));
    }

    public function actionLogout() {
        if (Yii::app()->user->isGuest) {
            $this->message(0, '您尚未登录');
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionReg() {
        if (!Yii::app()->user->isGuest) {
            $this->message(0, '您已登录，请勿重复操作');
        }
        $model = new Users();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-addUser-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['Users'])) {
            $username = zmf::filterInput($_POST['Users']['username'], 't', 1);
            $truename = zmf::filterInput($_POST['Users']['truename'], 't', 1);
            $ip = Yii::app()->request->userHostAddress;
            $hash=tools::randMykeys(6);
            $inputData = array(
                'username' => $username,
                'truename' => $truename,
                'password' => md5($_POST['Users']['password'].$hash),
                'email' => zmf::filterInput($_POST['Users']['email'], 't', 1),
                'qq' => zmf::filterInput($_POST['Users']['qq'], 't', 1),
                'telephone' => zmf::filterInput($_POST['Users']['telephone'], 't', 1),
                'groupid' => zmf::config('userDefaultGroup'),
                'cTime' => time(),
                'status' => 1,
                'ip' => ip2long($ip),
                'last_login_ip' => ip2long($ip),
                'last_login_time' => time(),
                'login_count' => 1,
                'hash'=>$hash,
            );
            $model->attributes = $inputData;
            if ($model->validate()) {
                if ($model->save()) {
                    $_model = new LoginForm;
                    $_model->username = $username;
                    $_model->password = $_POST['Users']['password'];
                    $_model->login();
                    $this->redirect(Yii::app()->createUrl('user/index'));
                }
            }
        }
        $data = array(
            'model' => $model
        );
        $this->_noColButOther = 'reg';
        $this->render('reg', $data);
    }

}
