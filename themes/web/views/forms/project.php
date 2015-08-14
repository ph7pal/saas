<?php $model=new Projects;?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'projects-project-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form-group"><?php echo $form->labelEx($model,'title'); ?><?php echo $form->textField($model,'title',array('class'=>'form-control')); ?></div><div class="form-group"><?php echo $form->labelEx($model,'desc'); ?><?php echo $form->textArea($model,'desc',array('class'=>'form-control')); ?></div><!--div class="form-group"><?php echo $form->labelEx($model,'expired_time'); ?><?php echo $form->textField($model,'expired_time',array('class'=>'form-control')); ?></div--><?php $this->endWidget();