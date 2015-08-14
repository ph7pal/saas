<?php
$css='';
if($info['expired_time']<zmf::now() && $info['expired_time']>0){
    $css='expired';
}
?>
<div class="panel panel-primary">
    <div class="panel-heading"><?php echo $project['title'];?></div>
    <div class="panel-body">
        <?php if(!empty($info['tags'])){?>
        <span class="label label-primary">Primary</span><span class="label label-primary">Primary</span>
        <?php }?>
        <h1 class="<?php echo $css;?>"><?php echo $info['title'];?></h1>
        <?php if($info['expired_time']>0){?>
        <p class="<?php echo $css;?>">截止时间：<?php echo zmf::time($info['expired_time'],'m-d H:i');?></p>
        <?php }?>
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
    <?php if(!empty($members)){?>
    <div class="panel-footer">参与者：<?php foreach($members as $mem){echo CHtml::link($mem['username'],'javascript:;').'&nbsp;';}?></div>
    <?php }?>
</div>