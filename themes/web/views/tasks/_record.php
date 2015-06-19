<p class="help-block">
    <?php echo $data->authorInfo->username;?> <?php echo TaskLog::exTypes($data->type,'desc');?> <span title="<?php echo $data['content'];?>"><?php echo zmf::subStr($data['content'],30);?></span><span class="pull-right"><?php echo tools::formatTime($data['cTime']);?></span>
</p>