<div class="programsettings form">
    <ul class="ttc-actions">		
        <li><?php echo $this->Html->tag('div', __('Save'), array('class'=>'ttc-button', 'id' => 'button-save')); ?></li>
        <?php $this->Js->get('#button-save')->event('click', '$("#ProgramSettingsEditForm").submit()' , true);?>
	</ul>
<H3><?php echo __('Edit Program Settings'); ?></H3>
  <div class="ttc-display-area">
  <?php echo $this->Form->create('ProgramSettings'); ?>
    <fieldset>      
        
        <div class='input text'>
        <?php
            echo $this->Html->tag('label',__('Shortcode'));    
            foreach($shortcodes as $shortcode) {
                $countyShortCode = trim($shortcode['ShortCode']['country'])." - ".$shortcode['ShortCode']['shortcode'];
                $prefixShortCode = $shortcode['ShortCode']['international-prefix']."-".$shortcode['ShortCode']['shortcode'];
                $shortcodeOptions[$prefixShortCode] = $countyShortCode;
                $shortcodeCompact[$prefixShortCode] = $shortcode['ShortCode'];
            }
            echo "<br />";
            echo $this->Form->select('shortcode', $shortcodeOptions, array('id' => 'shortcode'));
            //pack the shortcodes info to be easy to read in JS
            $this->Js->set('shortcodes', $shortcodeCompact);
            $this->Js->get('#shortcode')->event('change','
            			var countryShortcode = $("#shortcode option:selected").text();
            			var countryname = countryShortcode.slice(0, countryShortcode.lastIndexOf("-")-1);            			
            			$("#international-prefix").val(getCountryCodes(countryname));
            			var prefixShortcode = $("#shortcode").val();	
            			if (window.app.shortcodes[prefixShortcode]["support-customized-id"]=="1") {
            			    $("#customized-id").prop("disabled", false);
            			} else {
            			    $("#customized-id").prop("disabled", true);
            			    $("#customized-id").val("");
            			}
            			');
        ?>
        </div>
        <?php
            echo $this->Form->input(__('international-prefix'),
            		array('id' => 'international-prefix',
            		      'label' => 'International Prefix',
            		      'disabled' => 'disabled')
            		);
        ?>
        <div>
        <?php
            echo $this->Html->tag('label',__('Timezone'));
            $timezone_identifiers = DateTimeZone::listIdentifiers();
            $timezone_options = array();
            foreach($timezone_identifiers as $timezone_identifier) {
            $timezone_options[$timezone_identifier] = $timezone_identifier; 
            }
            echo "<br />";
            echo $this->Form->select('timezone', $timezone_options);
            //echo $this->Form->select('timezone', $timezone_identifiers, array('value'=>'412'));
        ?>
        </div><div>
        <?php 
            echo $this->Form->label(__('Default template for open questions'));
            echo "<br>";
            echo $this->Form->select('default-template-open-question', $openQuestionTemplateOptions, array(
                'empty'=> __('Template...')));
       ?>
        </div><div>
        <?php 
            echo $this->Form->label(__('Default template for closed questions'));
            echo "<br>";
            echo $this->Form->select('default-template-closed-question', $closedQuestionTemplateOptions, array(
                'empty'=> __('Template...')));
        ?>
        </div><div>
        <?php
	        echo $this->Html->tag('label',__('Default template for unmatching answers'));
	        echo "<br />";
	        echo $this->Form->select('default-template-unmatching-answer', $unmatchingAnswerTemplateOptions,
	            array('empty'=> __('Template...')));
	    ?>
	    </div>
	    <?php
	        $customizedIdDisabled = $shortcodeCompact[$this->data["ProgramSettings"]["shortcode"]]["support-customized-id"] ? false : true;
            echo $this->Form->input('customized-id',
            		array('id' => 'customized-id',
            		      'label' => 'Customized Id',
            		      'disabled' => $customizedIdDisabled)
            		);
        ?>
    </fieldset>
  <?php echo $this->Form->end(__('Save'));?>
  </div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
