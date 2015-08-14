<?php

/**
 * @filename _notice.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-8-14  11:00:54 
 */
?>
<p id="<?php echo tools::jiaMi($data['id']);?>" <?php if($data['new']){?>class="unread"<?php }?>>
    <?php echo $data['content'];?>
    <span class="pull-right" title="<?php echo zmf::time($data['cTime']);?>"><?php echo tools::formatTime($data['cTime']);?></span>
</p>