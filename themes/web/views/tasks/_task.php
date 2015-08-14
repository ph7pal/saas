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
if($data['expired_time']<zmf::now() && $data['expired_time']>0){
    $_itemCss.=' expired';
}
?>
<p class="zmf task-list <?php echo $_itemCss;?>" action="task" action-data="<?php echo tools::jiaMi($data['id']);?>">
    <span class="complete-btn <?php echo $_extraCss;?>" action="<?php echo $_action;?>"></span>
    <?php echo zmf::subStr($data['title'],30);?>
    <span class="pull-right"><?php echo $data['expired_time']>0 ? zmf::time($data['expired_time'],'m-d H:i') : '';?></span>
</p>