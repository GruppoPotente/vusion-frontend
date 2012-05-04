<div class="index" >
	<h3><?php echo __('Program Simulator'); ?></h3>
	<?php 
        if (isset($scripts)) {
            echo $this->Form->create(null, array('default'=>false));
            $options = array();
            foreach ($scripts as $label => $script) {
                $options[$script[0]['Script']['_id']] = $label;
                }
            echo $this->Form->select('script', $options, array('id' => 'script-selector', 'empty' => 'Existing Script...'));
            echo $this->Form->end();
            $this->Js->get('#script-selector')->event('change', '
	           window.location = window.location+"/"+$("select option:selected").val();
	   ');
	} else { 
	    echo $this->Html->tag('label', 'Exchanges');
	    echo $this->Html->tag('div', "it's going be here...", array('class'=>'ttc-simulator-output', 'id' => 'simulator-output'));
	    echo $this->Form->create(null, array('id'=>'simulator-input','default'=>false));
	    echo $this->Form->input('from', array('value' => 'phone here...', 'name'=>'participant-phone'));
	    echo $this->Form->input('message', array('value' => 'message here...', 'name'=>'message'));
	    echo $this->Form->end(array('label' => __('Send Message'), 'id'=>'send-button'));
	    $this->Js->get('#send-button')->event(
	           'click',
	           $this->Js->request(
	               array('program'=>$programUrl, 'action'=>'send.json'),
	               array('method' => 'POST',
                             'async' => true, 
	                     'dataExpression' => true,
	                     'data' => '$("#simulator-input").serialize()',
	                     'success' => 'logMessageSent()')));
	     $this->Js->get('document')->event(
	        'ready',
	        'setInterval(function(){pullSimulatorUpdate("'.$this->Html->url(array('program'=>$programUrl,'action'=>'receive.json')).'")}, 3000);');
	    }
       ?>
<?php echo $this->Js->writeBuffer(); ?>
