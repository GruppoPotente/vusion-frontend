<div class="users form">
<h3><?php echo __('Add User'); ?></h3>
<?php echo $this->Form->create('User');?>
	<fieldset>
		
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('group_id');
		$options = $programs;		
		echo $this->Form->input('Program', array('options'=>$options,
		    'type'=>'select',
		    'multiple'=>true,
		    'label'=>'Program',	                
		    'style'=>'margin-bottom:0px'
		    ));
	    $this->Js->get('document')->event('ready','$("#ProgramProgram").chosen();');	    
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Back to Admin menu'), array('controller' => 'admin', 'action' => 'index')); ?></li>
	</ul>
</div>
