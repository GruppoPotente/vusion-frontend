<div class="participants form">
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
        <?php $this->Js->get('#button-save')->event('click', '$("#ParticipantEditForm").submit()' , true);?>
	</ul>
	<h3><?php echo __('Edit Participant'); ?></h3>
	<div class="ttc-display-area">
	<?php echo $this->Form->create('Participant');?>
	    <fieldset>
	        <?php
	            echo $this->Form->input('phone');	            
	            $profiles = $this->data['Participant']['profile'];
	            if (is_array($profiles)) {
	                $profileArray = array();
	                foreach ($profiles as $profile) {
	                    $profileArray[] = $profile['label'].":".$profile['value'];
	                }
	                $profileData = implode(",", $profileArray);
	            } else {
	                $profileData = $profiles;
	            }
	            echo $this->Form->input(__('profile'), array('rows'=>5, 'value'=>$profileData));
	            $tags = $this->data['Participant']['tags'];
	            if (is_array($tags)) {
	                $tagsArray = explode(",",implode(",", $tags));
	                $tagsString = implode(", ",$tagsArray);
	            } else {
	                $tagsString = $tags;
	            }
	            echo $this->Form->input(__('tags'), array('rows'=>5, 'value'=>$tagsString));
	            $options = $selectOptions;
	            $selected = $oldEnrolls;
	            echo $this->Form->input('enrolled', array('options'=>$options,
	                'type'=>'select',
	                'multiple'=>true,
	                'label'=>'Enrolled In',
	                'selected'=>$selected,
                    'style'=>'margin-bottom:0px'
                    ));
	            $this->Js->get('document')->event('ready','$("#ParticipantEnrolled").chosen();');
	        ?>
	    </fieldset>
	<?php echo $this->Form->end(__('Save'));?>
	</div>
</div>
