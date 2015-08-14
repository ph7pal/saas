<div class="" style="width: 600px;margin: 200px auto">
    <?php if(!empty($groups)){?>
    <?php foreach($groups as $group){?>
    <div class="col-sm-6 col-md-6">
        <div class="thumbnail text-center">
            <p style="font-size: 96px;line-height: 200px;" class=""><i class="icon-group"></i></p>
          <div class="caption">
            <h3 id="thumbnail-label"><?php echo CHtml::link($group['title'],array('index/index','groupid'=>$group['id']));?></h3>
            <p><?php echo $group['desc'];?></p>
            <p><?php echo CHtml::link('进入',array('index/index','groupid'=>$group['id']),array('class'=>'btn btn-primary'));?></p>
          </div>
        </div>
      </div>    
    <?php }?>
    <?php }?>
</div>