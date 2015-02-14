<?php 
$origin_from=$from;
$from='_'.$from;
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'tasks-task-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->hiddenField($model,'pid',array('class'=>'form-control','id'=>CHtml::activeId($model,'pid').$from)); ?>
<?php echo $form->hiddenField($model,'tid',array('class'=>'form-control','id'=>CHtml::activeId($model,'tid').$from)); ?>
<?php echo $form->hiddenField($model,'expired_time',array('class'=>'form-control','id'=>CHtml::activeId($model,'expired_time').$from)); ?>    
<div class="input-group zmf">    
  <?php echo $form->textField($model,'title',array('class'=>'form-control zmf','placeholder'=>'添加新任务','action'=>'show','action-data'=>CHtml::activeId($model,'desc').$from.'_holder','id'=>CHtml::activeId($model,'title').$from)); ?>    
  <span class="input-group-btn">
      <button class="btn btn-default" type="button" action="assign" data-toggle="tooltip" data-placement="auto" title="指派任务"><span class="glyphicon glyphicon-user" action="assign"></span></button>
  </span>
  <span class="input-group-btn">
      <button class="btn btn-default date form_datetime" type="button" data-toggle="tooltip" data-placement="auto" title="指定完成期限" data-date="2015-02-10" data-date-format="dd MM yyyy" data-link-field="<?php echo CHtml::activeId($model,'expired_time').$from;?>"><span class="glyphicon glyphicon-calendar"></span></button>
  </span>
  <span class="input-group-btn">
      <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="auto" title="创建任务"><span class="glyphicon glyphicon-arrow-right" action="create-task" action-type="<?php echo $origin_from;?>"></span></button>
  </span>
</div>
<div class="form-group hidden" id="<?php echo CHtml::activeId($model,'desc').$from;?>_holder">
   <?php echo $form->textArea($model,'desc',array('class'=>'form-control','placeholder'=>'添加详细描述','id'=>CHtml::activeId($model,'desc').$from,'rows'=>1)); ?>
</div>
<?php $this->endWidget(); ?>