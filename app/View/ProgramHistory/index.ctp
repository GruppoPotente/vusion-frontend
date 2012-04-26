<div>
	
<div class="status index">
	
<div class="ttc-filter">
	
        <?php
	   echo $this->Form->create(null); 
	   $options = array(); 
	   $options['non_matching_answers'] = "Non matching answers";
	   if (isset($this->params['url']['filter']))
	        echo $this->Form->select('filter', $options, array('id'=> 'filter', 'style'=> 'float:right', 'default' => $this->params['url']['filter'],'empty' => 'Filter...'));
	   else 
	       	echo $this->Form->select('filter', $options, array('id'=> 'filter', 'style'=> 'float:right', 'empty' => 'Filter...'));
	   $this->Js->get('#filter')->event('change', '
	     if ($("select option:selected").val())
	         window.location.search = "?filter="+$("select option:selected").val();
             else
                 window.location.search = "?";
	   ');
	   echo $this->Form->end(); ?>
	</div>

        <h3 style='float:left'><?php echo __('Program History'); ?></h3>
        
	<table cellpadding="0" cellspacing="0">
	<tr>                                                                        
			<th><?php echo $this->Paginator->sort('phone', null, array('url'=> array('program' => $programUrl)));?></th>
			<th><?php echo $this->Paginator->sort('type', null, array('url'=> array('program' => $programUrl)));?></th>
			<th><?php echo $this->Paginator->sort('status', null, array('url'=> array('program' => $programUrl)));?></th>
			<th><?php echo $this->Paginator->sort('failure reason', null, array('url'=> array('program' => $programUrl)));?></th>
			<th><?php echo $this->Paginator->sort('message', null, array('url'=> array('program' => $programUrl)));?></th>
			<th><?php echo $this->Paginator->sort('time', null, array('url'=> array('program' => $programUrl)));?></th>
	</tr>
	<?php
	foreach ($statuses as $status): ?>
	<tr>
		<td><?php echo h($status['History']['participant-phone']); ?>&nbsp;</td>
		<td><?php echo h($status['History']['message-type']); ?>&nbsp;</td>
		<td><?php echo h($status['History']['message-status']); ?>&nbsp;</td>
		<td><?php if (isset($status['History']['failure-reason'])) echo h($status['History']['failure-reason']); ?>&nbsp;</td>
		<td><?php echo h($status['History']['message-content']); ?>&nbsp;</td>
		<td><?php echo $this->Time->format('d/m/Y H:i:s', $status['History']['timestamp']); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array('url'=> array('program' => $programUrl)), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => '', 'url'=> array('program' => $programUrl)));
		echo $this->Paginator->next(__('next') . ' >', array('url'=> array('program' => $programUrl)), null, array('class' => 'next disabled'));
	?>
</div>
	
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Back Homepage'), array('program'=>$programUrl,'controller'=>'programHome')); ?></li>
		<li><?php echo $this->Html->link('Export CSV', array('program' => $programUrl, 'action' => 'export.csv')); ?></li>
		<li><?php echo $this->Html->link('Export Raw CSV', array('program' => $programUrl, 'action' => 'index.csv')); ?></li>
		<li><?php echo $this->Html->link('Export Json', array('program' => $programUrl, 'action' => 'index.json')); ?></li>
	</ul>
</div>	
<?php echo $this->Js->writeBuffer(); ?>
