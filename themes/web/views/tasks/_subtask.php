<?php 
$_truename=$_extraCss=$_action=$_itemCss='';
if(is_array($data['uid'])){
    $_truename=$data['uid']['truename'];
}
if($data['status']==Tasks::STATUS_FINISHED){
    $_extraCss='icon-ok';
    $_itemCss='task-completed';
}elseif($data['status']==Tasks::STATUS_DELED){
    $_extraCss='icon-remove';
    $_itemCss='task-deled';
}else{
    $_extraCss='complete-task';
    $_action='complete-task';
}
?>
<p class="task-list zmf <?php echo $_itemCss;?>" action-data="<?php echo tools::jiaMi($data['id']);?>">
    <span class="<?php echo $_extraCss;?>" action="<?php echo $_action;?>"></span>
    <span data-toggle="tooltip" data-placement="auto" title="<?php echo $_truename.' 添加于 '.tools::formatTime($data['cTime']);?>"><?php echo $data['title'];?> </span><?php echo $data['expired_time']>0 ? tools::formatTime($data['expired_time']) : '';?>
    <span class="pull-right subtask-bar">
    <span class="glyphicon glyphicon-user" data-toggle="tooltip" data-placement="auto" title="指派任务"></span>
    <span class="glyphicon glyphicon-calendar date form_datetime" data-toggle="tooltip" data-placement="auto" title="指定完成期限" data-date="2015-02-10" data-date-format="dd MM yyyy"></span>    
    </span>
</p>