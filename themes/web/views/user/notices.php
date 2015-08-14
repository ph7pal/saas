<?php

/**
 * @filename notices.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-8-14  11:10:26 
 */
?>
<div class="panel panel-primary">
    <div class="panel-heading">提醒列表</div>
    <div class="panel-body">
        <?php if(!empty($notices)){?>  
          <?php foreach($notices as $notice){?>
          <?php $this->renderPartial('/user/_notice',array('data'=>$notice));?>
          <?php }?>
        <?php }else{?>
        <p class="help-block tips-holder">暂无提醒。</p>
        <?php }?>
    </div>
</div>