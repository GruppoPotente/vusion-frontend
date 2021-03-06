<div class="unattached_messages form">
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
    <?php $this->Js->get('#button-save')->event('click', '$("#UnattachedMessageEditForm").submit()' , true);?>
</ul>
<h3><?php echo __('Edit Separate Message'); ?></h3>
    <div class="ttc-display-area">
    <?php
    $sendToOptions = array(
        'all' => __('All participants'), 
        'match' => __('Participant matching'));
    $sendToMatchOperator = array(
        'all' => __('all'), 
        'any' => __('any'));
    $sendToMatchConditions = (isset($selectors) ? $selectors: array());;
    $errorSendTo = "";
    $errorSchedule = "";
    $scheduleOptions = array(
        'immediately'=>__('Immediately'),
        'fixed-time'=> __('Fixed Time:'));
    $matchSelectDisabled = true;
    $fixedTimeSelectDisabled = true;
        
    echo $this->Form->create('UnattachedMessage');
    echo $this->Form->input('name', array('id' => 'name')); 
    if ($this->Form->isFieldError('send-to-type') || 
        $this->Form->isFieldError('send-to-match-operator') || 
        $this->Form->isFieldError('send-to-match-conditions')) { 
            $errorSendTo = "error";       
    }
    echo "<div class=\"input-text required ".$errorSendTo."\">";
    echo $this->Html->tag('label',__('Send To'), array('class' => 'required'));
    echo "<br/>";
    echo $this->Form->radio('send-to-type', $sendToOptions, array('separator'=>'<br/>', 'legend'=>false, 'class' => 'sublabel no-after'));
    if (isset($this->Form->data['UnattachedMessage']['send-to-type']) &&
        $this->Form->data['UnattachedMessage']['send-to-type'] == 'match') {
        $matchSelectDisabled = false;
    }
    echo $this->Form->select(
        'send-to-match-operator',
        $sendToMatchOperator,
        array(
            'disabled' => $matchSelectDisabled, 
            'style'=>'margin-bottom:0px; margin-left:3px; margin-right: 3px', 
            'empty' => false));
    echo $this->Html->tag('label',__('of the following tag(s)/label(s):'));
    echo "<div class='subinput'>";
    echo $this->Form->select(
        'send-to-match-conditions', 
        $sendToMatchConditions, 
        array(
            'disabled' => $matchSelectDisabled,
            'multiple'=>true,
            'error' => false,
            'div' => false,
            'data-placeholder' => __('Choose from available tag(s)/label(s)...')));
    echo "</div>";
    if ($this->Form->isFieldError('send-to-type'))
        echo $this->Form->error('send-to-type');
    if ($this->Form->isFieldError('send-to-match-operator'))
        echo $this->Form->error('send-to-match-operator');
    if ($this->Form->isFieldError('send-to-match-conditions'))
        echo $this->Form->error('send-to-match-conditions');
    echo "</div>";
    echo $this->Form->input('content', array('rows'=>5));
    if ($this->Form->isFieldError('type-schedule') || 
        $this->Form->isFieldError('fixed-time')) { 
        $errorSchedule = "error";
    }
    echo "<div class='input-text required ".$errorSchedule."'>";
    echo $this->Html->tag('label',__('Schedule'), array('class' => 'required'));
    echo "<br />";
    echo $this->Form->radio('type-schedule', $scheduleOptions, array('separator'=>'<br/>', 'legend'=>false));
    if (isset($this->Form->data['UnattachedMessage']['type-schedule']) &&
        $this->Form->data['UnattachedMessage']['type-schedule'] == 'fixed-time') {
        $fixedTimeSelectDisabled = false;
    }
    echo "<div class='subinput'>";
    echo $this->Form->input('fixed-time', 
        array(
            'id'=>'fixed-time', 
            'label'=>false, 
            'disabled' => $fixedTimeSelectDisabled, 
            'error' => false,
            'div' => false,
            'placeholder' => "Choose a fixed time..."));
    echo "</div>";
    if ($this->Form->isFieldError('type-schedule')) {
        echo $this->Form->error('type-schedule');
    } elseif ($this->Form->isFieldError('fixed-time')) {
        echo $this->Form->error('fixed-time');
    } 
    echo "</div>";
    $this->Js->get('document')->event('ready','
        $("#fixed-time").datetimepicker();
        addContentFormHelp();
        addCounter();
        $("#UnattachedMessageSend-to-match-conditions").chosen();');
    $this->Js->get("input[name*='send-to-type']")->event('change','
        if ($(this).val() == "match" ) {
        $("select[name*=\"send-to-match-conditions\"]").attr("disabled",false).trigger("liszt:updated");
        $("select[name*=\"send-to-match-operator\"]").attr("disabled",false);
        } else {
        $("select[name*=\"send-to-match-conditions\"]").attr("disabled", true).val("").trigger("liszt:updated");
        $("select[name*=\"send-to-match-operator\"]").attr("disabled",true);
        }');
    $this->Js->get("input[name*='type-schedule']")->event('change','
        if ($(this).val() == "fixed-time" ) {
        $("#fixed-time").attr("disabled",false);
        } else {
        $("#fixed-time").attr("disabled","disabled");
        $("#fixed-time").val("");
        }');
    echo $this->Form->end(__('Save'));?>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
