<p class="help-block">
    <?php echo $data->authorInfo->username;?>：<?php echo CHtml::encode($data['content']);?><span class="pull-right"><?php echo tools::formatTime($data['cTime']);?></span>
</p>