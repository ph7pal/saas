<!DOCTYPE HTML>
<html lang="zh-CN">
    <head>           
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
        <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1">
        <meta content="yes" name="apple-mobile-web-app-capable" />
        <meta content="black" name="apple-mobile-web-app-status-bar-style"  />
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="full-screen" content="yes">
        <meta name="format-detection" content="telephone=no">    
        <meta name="format-detection" content="address=no">
        <?php 
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->baseUrl.'/common/css/bootstrap.min.css');
        $cs->registerCssFile(Yii::app()->baseUrl.'/common/css/bootstrap-datetimepicker.min.css');
        $cs->registerCssFile(Yii::app()->baseUrl.'/common/css/dashboard.css');
        $cs->registerCssFile(Yii::app()->baseUrl . '/common/css/font-awesome.min.css');
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/bootstrap.min.js", CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/bootstrap-datetimepicker.zh-CN.js", CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->baseUrl . "/common/js/zmf.js", CClientScript::POS_END);
        ?>
        <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>
    <div class="col-sm-2 col-xs-2 sidebar no-padding">
            <div class="media nav-sidebar">
                <div class="media-left">
                  <a href="#">                        
                      <span class="" style="font-size:64px;line-height: 64px;"><i class="icon-user"></i></span>
                  </a>
                </div>
                <div class="media-body">
                    <p><b><?php echo $this->userInfo['username'];?></b><span class="pull-right"><?php echo CHtml::link('退出',array('site/logout'));?></span></p>
                    <p>团队：<?php echo $this->groupInfo['title'];?></p>
                    <p>待办：<span id="todo-num">0</span></p>
                </div>
            </div>
        <div class="clearfix"></div>
      <ul class="nav nav-sidebar">
          <li><a href="javascript:;" class="zmf autoload" action="my-tasks" action-data="<?php echo tools::jiaMi($this->uid);?>"><span class="glyphicon glyphicon-th-list"></span> 我的任务</a></li>
          <li><a href="javascript:;" class="zmf" action="my-notices"><span class="glyphicon glyphicon-bell"></span> 提醒<span class="badge pull-right <?php echo $this->userInfo['noticeNum']>0 ? 'red-badge' :'';?>" id="my-notices-num"><?php echo $this->userInfo['noticeNum'];?></span></a></li>
          <!--li><a href="javascript:;" class="zmf" action="my-notices" action-data="<?php echo tools::jiaMi($this->uid);?>"><span class="glyphicon glyphicon-calendar"></span> 黑板报</a></li>
          <li><a href="javascript:;" class="zmf" action="settings" action-data="<?php echo tools::jiaMi($this->uid);?>"><span class="glyphicon glyphicon-cog"></span> 设置</a></li-->
      </ul>
      <ul class="nav nav-sidebar">
          <?php if($this->userInfo['groupid']>0){?>              
          <h2 class="section-title">团队成员 <span class="icon-plus zmf" action="new-member"></span></h2>
          <?php if(!empty($this->members)){?>
          <?php foreach($this->members as $mem){?><li><?php if($mem['status']){echo CHtml::link($mem['username'],'javascript:;',array('class'=>'zmf','action'=>'my-tasks','action-data'=>tools::jiaMi($mem['id'])));}else{echo CHtml::link($mem['username'].'<span class="pull-right">待激活</span>','javascript:;');}?></li><?php }?>
          <?php }?>
          <?php }else{?>
          <h2 class="section-title">团队成员</h2>
          <p class="text-center"><?php echo CHtml::link('创建团队','javascript:;',array('class'=>'btn btn-default zmf','action'=>'new-group'));?></p>
          <?php }?>
      </ul>
      <ul class="nav nav-sidebar" id="list-projects">
          <h2 class="section-title">我的项目 <span class="icon-plus zmf" action="create-project"></span></h2>
          <?php if(!empty($this->projects)){foreach($this->projects as $project){?>
          <li><?php echo CHtml::link($project['title'],'javascript:;',array('class'=>'zmf','action'=>'project','action-data'=>tools::jiaMi($project['id'])));?></li>
          <?php }}?>
      </ul>
    </div>
    <div class="main col-sm-10 col-md-10 no-padding">
        <div class="col-sm-6 col-md-6 no-padding" id="project-tasks-holder"></div>
        <div class="col-sm-6 col-md-6 holder-right no-padding" id="task-detail-holder">
            <p class="help-block">请选择任务以查看详情</p>                  
        </div>
    </div>
    <script>
        var configs={};
        configs.createProjectHtml='<?php echo $this->renderPartial('/forms/project',NULL,false);?>';
        configs.newMemberHtml='<?php echo $this->renderPartial('/forms/newMember',NULL,false);?>';
        configs.newGroupHtml='<?php echo $this->renderPartial('/forms/newGroup',NULL,false);?>';
        configs.csrfToken='<?php echo Yii::app()->request->csrfToken;?>';
        configs.sessionId='<?php echo Yii::app()->session->sessionID;?>';
        configs.handleUrl='<?php echo Yii::app()->createUrl('form/index',array('groupid'=>  tools::jiaMi($this->groupid)));?>';
        $(function(){
            sidebar();
            xunhuan();
            $('.autoload').click();
            $(window).resize(function() {
                sidebar();
            });
        });
    </script>
    </body>
</html>