<div class="task-lists" id="project-tasks-list">
    <?php if(!empty($tasks)){?>  
      <?php foreach($tasks as $task){?>
      <?php $this->renderPartial('/tasks/_task',array('data'=>$task));?>
      <?php }?>
    <?php }else{?>
    <p class="help-block tips-holder">该项目下暂无任务</p>
    <?php }?>
 </div>
<?php $this->renderPartial('/forms/task',array('model'=>$model,'from'=>'project'));?>