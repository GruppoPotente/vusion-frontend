<div class="users form">
<h3><?php echo __('Add User'); ?></h3>
<?php echo $this->Form->create('User');?>
	<fieldset>
		
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('group_id');
		echo $this->Form->input('Program');
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
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Back to Admin menu'), array('controller' => 'admin', 'action' => 'index')); ?></li>
	</ul>
</div>
