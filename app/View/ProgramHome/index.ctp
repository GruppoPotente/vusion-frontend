<div>
	<!--<h2><?php echo $programName . ' ' .  __('Home');?></h2>-->
	<div class='ttc-actions'>
	<h3><?php echo __('Status & Actions');?></h3>
	<?php if (!$hasScriptActive && !$hasScriptDraft) { ?>
		<div class='ttc-info-box'>
		<?php
		     echo $this->Html->tag('div', 
			'No script has been defined for this program',
			array('class' => 'ttc-text')
			);
		     if ($isScriptEdit) {
		        echo $this->Html->link('Create script', 
			    array('program' => $programUrl,
			      'controller' => 'programScripts',
			      'action' => 'draft'
			      ),
			array('class' => 'ttc-button')
			);
			}; 
		 ?>
		 </div>
		<?php } else { ?>
		<?php
	       	  if ($hasScriptDraft) { ?>
	       	 <div class='ttc-info-box'>
	       	  <?php
			echo $this->Html->tag('div', 
				'A draft script has been defined for this program',
				array('class' => 'ttc-text')
				);
			if ($isScriptEdit) {	
			echo $this->Html->link('Edit draft', 
				array('program' => $programUrl,
				      'controller' => 'programScripts',
				      'action' => 'draft'
				      ),
				array('class' => 'ttc-button')
				);
			echo $this->Html->link('Activate draft', 
				array('program' => $programUrl,
				      'controller' => 'programScripts',
				      'action' => 'activateDraft'
				      ),
				array('class' => 'ttc-button')
				);
			/*echo $this->Html->tag('span', 'Activate draft',
				array('class' => 'ttc-button',
					'id'=> 'activate-button')
				);
			$this->Js->get('#activate-button')->event('click','$.get(
				"'.$programName.'/scripts/activateDraft"
				);', true);*/
			} ?> 
			</div>
			<?php
		  }; 
		  if ($hasScriptActive) {
		  ?>
		  
		  <div class='ttc-info-box'>
		  <?php
			echo $this->Html->tag('div', 
				'A script is already active for this program',
				array('class' => 'ttc-text')
				);
			if ($isScriptEdit) {
			echo $this->Html->link('Edit script', 
				array('program' => $programUrl,
				      'controller' => 'programScripts',
				      'action' => 'active'
				      ),
				array('class' => 'ttc-button')
				);
			}
			?>
			</div>
			<?php
			if (isset($workerStatus)) {
			?>
			<div class='ttc-info-box'>
			<?php 
			if ($workerStatus['running']) {
			     echo $this->Html->tag('div', 
			         'Vumi has a worker for this script', 
			         array('class'=>'ttc-text')
			         );
			} else {
			     echo $this->Html->tag('div', 
			         "WARNING Vumi DOESN'T have a worker for this script",
			         array('class'=>'ttc-text'));
			}
			?>
			</div>
			<?php
			};
		  }; 
	       } ?>

	<div class='ttc-info-box'>
	<?php echo $this->Html->tag('div', 
				'Participants: '.$participantCount,
				array('class' => 'ttc-text')
				); ?>
	<?php if ($isParticipantAdd) { 
		echo $this->Html->link('Add participant',
			array('program' => $programUrl,
				'controller' => 'programParticipants',
				'action' => 'add'
				),
			array('class' => 'ttc-button')
			);
		}?>
	<?php if ($participantCount > 0) {
		echo $this->Html->link('View participant(s)',
			array('program' => $programUrl,
				'controller' => 'programParticipants', 
				),
			array('class' => 'ttc-button')
			);
		}?>
	<br /><br />
	<?php echo $this->Html->link(__('Import Participant(s)'),
			array('program'=> $programUrl, 
	                'controller' => 'programParticipants', 
                        'action' => 'import'),
                         array('class' => 'ttc-button')
                         );
                ?>
	</div>
	<div class='ttc-info-box'>
	<?php echo $this->Html->tag('div', 
				'Program History: '.$statusCount.' item(s)',
				array('class' => 'ttc-text')
				); ?>
	<?php if ($statusCount > 0) {
		echo $this->Html->link('View Program History',
			array('program' => $programUrl,
				'controller' => 'programHistory', 
				),
			array('class' => 'ttc-button')
			);
		}?>
	</div>
	<div class='ttc-info-box'>
	    <?php echo $this->Html->link(__('Program Settings'),
	                array('program'=> $programUrl, 
	                'controller' => 'programSettings', 
                        'action' => 'index'),
                         array('class' => 'ttc-button')
                         );
            ?>
            <?php echo $this->Html->link(__('Back To Program List'),
			array('controller' => 'programs', 
                        'action' => 'index'),
                         array('class' => 'ttc-button')
                         );
            ?>
	</div>
	</div>
	<div class='ttc-info'>
	<h3><?php echo __('Sending Next');?></h3>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('at');?></th>
			<th><?php echo __('to');?></th>
		</tr>
	<?php
	foreach ($schedules as $schedule): ?>
	<tr>
		<td><?php echo h($schedule['Schedule']['datetime']); ?>&nbsp;</td>
		<td><?php echo h($schedule['Schedule']['participant-phone']); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
	</table>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>