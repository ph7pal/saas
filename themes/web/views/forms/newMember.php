<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'new-member-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form-group"><label>对方邮箱</label><?php echo CHtml::textField('new-member-email','',array('class'=>'form-control')); ?></div><?php $this->endWidget();