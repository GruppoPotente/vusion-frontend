<div class="unattached_messages index">
	<h3><?php echo __('Unattached Messages');?></h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('To');?></th>
			<th><?php echo $this->Paginator->sort('Content');?></th>
			<th><?php echo $this->Paginator->sort('Schedule');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($unattachedMessages as $unattachedMessage): ?>
	<tr>
		<td><?php echo h($unattachedMessage['UnattachedMessage']['to']); ?>&nbsp;</td>
		<td><?php echo h($unattachedMessage['UnattachedMessage']['content']); ?>&nbsp;</td>
		<td><?php echo h($unattachedMessage['UnattachedMessage']['schedule']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('program'=>$programUrl, 'action' => 'edit', $unattachedMessage['UnattachedMessage']['_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('program'=>$programUrl, 'action' => 'delete', $unattachedMessage['UnattachedMessage']['_id']), null,
			                                __('Are you sure you want to delete # %s?', $unattachedMessage['UnattachedMessage']['_id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table> 
	<p>
	   <?php
	      echo $this->Paginator->counter(array(
	      'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	      ));
	   ?>
	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Back Homepage'), array('program'=>$programUrl,'controller'=>'programHome')); ?></li>
		<li><?php echo $this->Html->link(__('New Unattached Message'), array('program'=>$programUrl, 'action' => 'add')); ?></li>
	</ul>
</div>