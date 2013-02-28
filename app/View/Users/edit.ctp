<div class="users form">
<h3><?php echo __('Edit User'); ?></h3>
<?php echo $this->Form->create('User');?>
	<fieldset>
		
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
	?>
	<div class='input text'>
	<?php
		echo $this->Html->tag('label',__('Password'));
		echo $this->Html->link(__('Change Password'), array('action' => 'changePassword', $this->Form->value('User.id')));
	?>
	</div>
	<?php
		echo $this->Form->input('email');
		if (isset($isAdmin) && $isAdmin) {
		    echo $this->Form->input('group_id');
		    echo $this->Form->input('Program');
		}
	?>
	<div class='input text'>
	<?php
	$filePath = WWW_ROOT . "files";
        $fileName = "Secret Questions.csv";
        $importedQuestions = fopen($filePath . DS . $fileName,"r");
        $questions = array();
        $count = 0;
        $options = array();
        while (!feof($importedQuestions)) {
            $questions[] = fgets($importedQuestions);
            if ($count > 0 && $questions[$count]) {
                $questions[$count] = str_replace("\n", "", $questions[$count]);
                $explodedLine = explode(",", $questions[$count]);
                $options[trim($explodedLine[0])] = trim($explodedLine[0]);            
            }
            $count++;
        }    
        echo $this->Html->tag('label', __('Secret Questions'));
        echo "<br>";
        echo $this->Form->select('Secret Questions', $options);
        ?>
        </div>
    <?php
        echo $this->Form->input('password', array('label' => 'Secret Answer'));        
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php 
		if (isset($isAdmin) && $isAdmin) {
		    echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $this->Form->value('User.id')), null, __('Are you sure you want to delete the user "%s" ?', $this->Form->value('User.username'))); 
		}
		?>
		</li>
		<li><?php
		if (isset($isAdmin) && $isAdmin) {
		    echo $this->Html->link(__('List Users'), array('action' => 'index'));
		}
		?>
		</li>
		<li><?php 
		if (isset($isAdmin) && $isAdmin) {
		echo $this->Html->link(__('Back to Admin menu'), array('controller' => 'admin', 'action' => 'index'));
		}else{
		echo $this->Html->link(__('Back to Programs'), array('controller' => 'programs', 'action' => 'index')); 
		}
		?></li>
	</ul>
</div>
