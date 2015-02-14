<p class="zmf task-list" action="task" action-data="<?php echo tools::jiaMi($data['id']);?>">
    <span class="glyphicon complete-task" action="complete-task"></span>
    <span data-toggle="tooltip" data-placement="auto" title="<?php echo $_truename.' 添加于 '.tools::formatTime($data['cTime']);?>"><?php echo $data['title'];?> </span><?php echo $data['expired_time']>0 ? tools::formatTime($data['expired_time']) : '';?>
    <span class="pull-right subtask-bar">
    <span class="glyphicon glyphicon-user" data-toggle="tooltip" data-placement="auto" title="指派任务"></span>
    <span class="glyphicon glyphicon-calendar date form_datetime" data-toggle="tooltip" data-placement="auto" title="指定完成期限" data-date="2015-02-10" data-date-format="dd MM yyyy"></span>
    <span class="glyphicon glyphicon-chevron-right"></span>
    </span>
</p>