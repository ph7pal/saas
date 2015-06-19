<?php
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
<p class="zmf task-list <?php echo $_itemCss;?>" action="task" action-data="<?php echo tools::jiaMi($data['id']);?>">
    <span class="glyphicon <?php echo $_extraCss;?>" action="<?php echo $_action;?>"></span>
    <span data-toggle="tooltip" data-placement="auto" title="<?php echo $_truename.' 添加于 '.tools::formatTime($data['cTime']);?>"><?php echo zmf::subStr($data['title'],30);?> </span><?php echo $data['expired_time']>0 ? tools::formatTime($data['expired_time']) : '';?>
</p>