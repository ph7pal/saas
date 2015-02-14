<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'tasks-task-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->hiddenField($model,'expired_time',array('class'=>'form-control')); ?>    
<div class="input-group">
  <?php echo $form->textField($model,'title',array('class'=>'form-control zmf','placeholder'=>'添加新任务')); ?>
  <span class="input-group-btn">
      <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="auto" title="指派任务"><span class="glyphicon glyphicon-user"></span></button>
  </span>
  <span class="input-group-btn">
      <button class="btn btn-default date form_datetime" type="button" data-toggle="tooltip" data-placement="auto" title="指定完成期限" data-date="2015-02-10" data-date-format="dd MM yyyy" data-link-field="<?php echo CHtml::activeId($model,'expired_time');?>"><span class="glyphicon glyphicon-calendar"></span></button>
  </span>
</div>
<?php $this->endWidget(); ?>