<div class="panel panel-primary">
    <div class="panel-heading"><?php echo $project['title'];?></div>
    <div class="panel-body">
        <span class="label label-primary">Primary</span><span class="label label-primary">Primary</span>
        <h1><?php echo $info['title'];?></h1>
        <textarea class="form-control" placeholder="添加描述" rows="1"><?php echo $info['desc'];?></textarea>
        <hr/>
        <div class="task-lists" id="task-subtasks-<?php echo tools::jiaMi($info['id']);?>">
        <?php if(!empty($subtasks)){?>
        <?php foreach($subtasks as $task){?>
        <?php $this->renderPartial('/tasks/_subtask',array('data'=>$task));?>
        <?php }?>
        <?php }?>
        </div>
        <?php $this->renderPartial('/forms/task',array('model'=>$model,'from'=>'task'));?>
        <hr/>
        <div id="task-comments-<?php echo tools::jiaMi($info['id']);?>">  
            <?php if(!empty($records)){?>
            <?php foreach($records as $record)$this->renderPartial('/tasks/_record',array('data'=>$record));?>
            <?php }?>
            <?php if(!empty($comments)){?>
            <?php foreach($comments as $com)$this->renderPartial('/tasks/_comment',array('data'=>$com));?>
            <?php }?>
        </div>
        <div class="form-group"><textarea class="form-control" id="comment-<?php echo tools::jiaMi($info['id']);?>" placeholder="添加评论" rows="1"></textarea></div>
        <div class="form-group"><a class="btn btn-primary btn-xs zmf" action="comment" data-loading-text="提交..." action-data="<?php echo tools::jiaMi($info['id']);?>">提交</a></div>
    </div>
    <div class="panel-footer">参与者：<a>大飞</a></div>
</div>