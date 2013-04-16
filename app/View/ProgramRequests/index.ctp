<div class='Program Requests index'>
	<ul class="ttc-actions">
		<li><?php echo $this->Html->link(__('New Request'), array('program'=>$programUrl, 'action' => 'add'), array('class' => 'ttc-button')); ?></li>
	</ul>	
	<h3><?php echo __('Requests');?></h3>
	<div class="ttc-data-control">
	<div id="data-control-nav" class="ttc-paging paging">
	<?php
	echo "<span class='ttc-page-count'>";
	echo $this->Paginator->counter(array(
	    'format' => __('{:start} - {:end} of {:count}')
	    ));
	echo "</span>";
	echo $this->Paginator->prev('<', array('url'=> array('program' => $programUrl, '?' => $this->params['url'])), null, array('class' => 'prev disabled'));
	//echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(' >', array('url'=> array('program' => $programUrl, '?' => $this->params['url'])), null, array('class' => 'next disabled'));
	?>
	</div>
	</div>
	<div class="ttc-table-display-area">
	<div class="ttc-table-scrolling-area">
	<table cellpadding="0" cellspacing="0">
	    <thead>	
	        <tr>
			    <th class="direction"><?php echo $this->Paginator->sort('keyword', null, array('url'=> array('program' => $programUrl)));?></th>
			    <th class="responses"><?php echo $this->Paginator->sort('responses', null, array('url'=> array('program' => $programUrl)));?></th>
			    <th class="status"><?php echo $this->Paginator->sort('do', null, array('url'=> array('program' => $programUrl)));?></th>
			    <th class="action-requests"><?php echo __('Actions');?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		    foreach ($requests as $request): ?>
		    <tr>
		        <td class="direction"><?php echo $request['Request']['keyword']; ?>&nbsp;</td>
		        <td class="responses">
		            <?php 
		            if (isset($request['Request']['responses']))
		            foreach ($request['Request']['responses'] as $response) {
		                echo $this->Html->tag('div', '"'.$response['content'].'"');
		            } 
		            ?>
		        </td>
		        <td class="status">
		            <?php 
		            if (isset($request['Request']['actions']))
		            foreach ($request['Request']['actions'] as $action) {
		                $info = __($action['type-action']);
		                if ($action['type-action']=='enrolling')
		                    foreach ($dialogues as $dialogue) {
		                        if ($dialogue['dialogue-id'] == $action['enroll']) {
		                            $info = __("Enrolling dialogue %s", $dialogue['Active']['name']);
		                            break;
		                        }
		                    }
		                    echo $this->Html->tag('div', $info);
		            } 
		            ?>
		        </td>
		        <td class="action-requests actions">
		            <?php echo $this->Html->link(__('Edit'), array('program'=>$programUrl, 'action' => 'edit', $request['Request']['_id'])); ?>
		            <?php echo $this->Form->postLink(__('Delete'), array('program'=>$programUrl, 'action' => 'delete', $request['Request']['_id']), null,
			                                __('Are you sure you want to delete %s?', $request['Request']['keyword'])); ?>
			    </td>
			    </tr>
			        <?php endforeach; ?>
			  </tbody>
			</table>
		</div>
	</div>	
</div>
	
<?php echo $this->Js->writeBuffer(); ?>
