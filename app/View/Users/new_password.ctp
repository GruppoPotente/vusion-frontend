 <h2><?php echo __('Enter New Password')?> </h2>
    <?php
        echo $this->Form->create('User', array('controoler' => 'users', 'action' => 'newPassword', $userId));
        echo $this->Form->input('password', array('label' => 'New Password', 'id' => 'newPassword', 'name' => 'newPassword'));
        echo $this->Form->input('password', array('label' => 'Confirm New Password', 'id' => 'confirmNewPassword', 'name' => 'confirmNewPassword'));
        echo $this->Form->end(__('Save',true));
    ?>
