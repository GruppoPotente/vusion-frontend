<div class="shortcodes form">
<h3><?php echo __('Add ShortCode'); ?></h3>
<?php echo $this->Form->create('ShortCode');?>

	<fieldset>
		
		<div class='input text'>
	<?php	
		echo $this->Html->tag('label',__('Country'));
		echo "<br />";
		echo $this->Form->select('country', $countryOptions, array('id'=> 'country'));
		$this->Js->get('#country')->event('change', '		       
		       $("#international-prefix").val(getCountryCodes($("#country option:selected").text()));
		       ');
	?>
		</div>
	<?php
		echo $this->Form->input(__('shortcode'));
		echo $this->Form->input('international-prefix',
				array('id' => 'international-prefix',
					'label' =>__('International Prefix'),
					'readonly' => true)
					);
	?>
	<div>
	<?php
	    echo $this->Html->tag('label',__('Error Template'));
		echo "<br />";
	    echo $this->Form->select('error-template', $errorTemplateOptions,
	        array('id' => 'error-template',
	            'empty'=> __('Template...')
	            )
	        );
	?>
	</div>
	<div>
	<?php
	    echo $this->Html->tag('label',__('Support Customized Id'));
	    echo $this->Form->checkbox('support-customized-id');
	?>
	</div>
	<div>
	<?php
	    echo $this->Html->tag('label',__('Supported Internationally'));
	    echo $this->Form->checkbox('supported-internationally');
	?>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
	    <li><?php echo $this->Html->link(__('View Shortcodes'), array('action'=>'index')); ?></li>
		<li><?php echo $this->Html->link(__('Back to Admin menu'), array('controller' => 'admin', 'action' => 'index')); ?></li>
	</ul>
	
</div>
<?php echo $this->Js->writeBuffer(); ?>
