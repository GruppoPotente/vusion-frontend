<div class="index">
    <ul class="ttc-actions">
		<li>
		<?php echo $this->Html->tag('span', __('Save'), array('class'=>'ttc-button', 'id' => 'button-save')); ?>
		<span class="actions">
		<?php
		echo $this->Html->link( __('Cancel'), 
		    array(
		        'program' => $programUrl,
		        'controller' => 'programHome',
		        'action' => 'index'	           
		        ));
		?>
		</span>
		</li>
		<?php $this->Js->get('#button-save')->event('click','
		    disableSaveButtons();		    
		    $("#dynamic-generic-program-form").submit();
		    ', true);?>
		<?php $this->Js->get('#dynamic-generic-program-form')->event('submit','
		    disableSaveButtons();'); ?>
        <?php 
        if (isset($dialogue)) {
            if (!$dialogue['Dialogue']['activated']) {
                echo "<li>";
                echo $this->Html->link(__('Activate'), array('program'=>$programUrl,'action'=>'activate', 'id'=>$dialogue['Dialogue']['_id']), array('class'=>'ttc-button'));
                echo "</li>";
            } 
            ## Remove simulate button as long as it's not properly working in the backend
            /*echo "<li>";
            echo $this->Html->link(__('Simulate'), array('program'=>$programUrl, 'controller' => 'programSimulator', 'action'=>'simulate', 'id'=>$dialogue['Dialogue']['_id']), array('class'=>'ttc-button'));
            echo "</li>";*/
            echo "<li>";
            echo $this->Html->link(__('Test send all messages'), array('program'=>$programUrl,'action'=>'testSendAllMessages', 'id'=>$dialogue['Dialogue']['_id']), array('class'=>'ttc-button'));
            echo "</li>"; 
        }?>
	</ul>
	<h3>
	<?php 
	if (isset($dialogue)) 
	    echo __('Edit Dialogue'); 
	else
	    echo __('Create Dialogue');
	?>
	<?php
	if (isset($dialogue) && !$dialogue['Dialogue']['activated'])  
	    	    echo $this->Html->tag('span', __('(draft)', array('class'=>'ttc-dialogue-draft'))); 
	?>
	</h3>
	<div class="ttc-display-area">
	<?php echo $this->Html->tag('form', null, array(' id'=> 'dynamic-generic-program-form')); ?>
	<?php
	$this->Js->get("#dynamic-generic-program-form");
	if (isset($dialogue))
	    $this->Js->each('$(this).buildTtcForm("Dialogue", '.$this->Js->object($dialogue['Dialogue']).', "javascript:saveFormOnServer()")', true);
	else
	$this->Js->each('$(this).buildTtcForm("Dialogue", null, "javascript:saveFormOnServer()")', true);
    ?>
	</div>
	<?php
	$offsetConditionOptions[] = array('value'=>'0', 'html' => __('Choose one question...'));
	if (isset($dialogue['Dialogue']['interactions'])) {
	    foreach($dialogue['Dialogue']['interactions'] as $interaction) {
	        if ($interaction['type-interaction']!='question-answer' and $interaction['type-interaction']!='question-answer-keyword')
	            continue;
	        $offsetConditionOptions[] = array(
	            'value' => $interaction['interaction-id'],
	            'html' => (isset($interaction['content']) ? $interaction['content'] : "")
	            );
	    }
	}
	$this->Js->set('offset-condition-interaction-idOptions', $offsetConditionOptions);
	
	$dialogueOptions = array();
	foreach($dialogues as $dialogue) {
	    if ($dialogue['Active']) {
	        $dialogueOptions[] = array(
	            'value' => $dialogue['Active']['dialogue-id'],
	            'html' => $dialogue['Active']['name']
	            );
	    }
	}
	$this->Js->set('enrollOptions', $dialogueOptions);
	?>
</div>
<?php echo $this->Js->writeBuffer(); ?>
