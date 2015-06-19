<div class="panel panel-primary">
    <div class="panel-heading">任务列表</div>
    <div class="panel-body">
        <div class="task-lists" id="project-tasks-list">
            <?php if(!empty($tasks)){?>  
              <?php foreach($tasks as $task){?>
              <?php $this->renderPartial('/tasks/_task',array('data'=>$task));?>
              <?php }?>
            <?php }else{?>
            <p class="help-block tips-holder">该项目下暂无任务</p>
            <?php }?>
         </div>
        <?php if($type!='my-task'){?>
        <?php $this->renderPartial('/forms/task',array('model'=>$model,'from'=>'project'));?>
        <?php }?>
    </div>
    <?php if(!empty($members)){?>
    <div class="panel-footer">参与者：<a>大飞</a></div>
    <?php }?>
</div>