    <h2><?php echo __('Reset Password')?> </h2>
    <?php echo $this->Form->create('User', array('controller' => 'users', 'action' => 'resetPassword'));?>
    
    <?php  
       echo $this->Form->input('text', array('label' => 'Email', 'id' => 'email', 'name' => 'email')); 
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
        echo $this->Form->input('password', array('label' => 'Secret Answer', 'id' => 'secretAnswer', 'name' => 'secretAnswer'));        
        echo $this->Form->end(__('Save',true));
    ?>
