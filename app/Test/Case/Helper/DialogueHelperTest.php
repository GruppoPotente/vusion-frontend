<?php

App::uses('DialogueHelper', 'Lib');

class DialogueHelperTestCase extends CakeTestCase
{
    protected function getOneScript($keyword)
    {
        $script['Script'] = array(
            'script' => array(
                'dialogues' => array(
                    array(
                        'dialogue-id'=> 'script.dialogues[0]',
                        'interactions'=> array(
                            array(
                                'type-interaction' => 'annoucement', 
                                'content' => 'hello',
                                'keyword' => 'feel',
                                'interaction-id' => 'script.dialogues[0].interactions[0]'
                                ),	
                            array(
                                'type-interaction' => 'question-answer', 
                                'content' => 'how are you', 
                                'keyword' => $keyword,
                                'type-question'=>'close-question',
                                'answers'=> array(
                                    0 => array('choice'=>'Good'),
                                    1 => array('choice'=>'Bad')
                                    ),
                                'interaction-id' => 'script.dialogues[0].interactions[1]'
                                )
                            )
                        )
                    )
                )
            );

        return $script;
    }
    
    
    public function testHasKeyword()
    {
        $DialogueHelper = new DialogueHelper();
        $script = array('keyword'=>'keyword');
        $this->assertEquals($script['keyword'],$DialogueHelper->hasKeyword($script, "keyword"));
        
        $script = $this->getOneScript('keyword');
        $this->assertEquals(
            $script['Script']['script']['dialogues'][0]['interactions'][0]['keyword'],
            $DialogueHelper->hasKeyword($script, "feel")
        );
        
        $script = $this->getOneScript('keyword');
        $this->assertEquals(
            $script['Script']['script']['dialogues'][0]['interactions'][1]['keyword'],
            $DialogueHelper->hasKeyword($script, "keyword")
        );
    }
    
    
    public function testHasNoMatchingAnswers()
    {
        $DialogueHelper = new DialogueHelper();
        $script = array('keyword'=>'keyword',
        	'answers'=> array(
        	    0 => array('choice' => 'Bad'),
        	    1 => array('choice' => 'Good')
        	)
        );
        
        $status = array('message-content'=>'keyword Good');
        $this->assertFalse($DialogueHelper->hasNoMatchingAnswers($script, $status));
                
        $status = array('message-content'=>'keyword Goo');
        $this->assertTrue($DialogueHelper->hasNoMatchingAnswers($script, $status));
        
        $script = $this->getOneScript('keyword');
        $status = array('message-content'=>'keyword Bad');
        $this->assertFalse($DialogueHelper->hasNoMatchingAnswers($script, $status));
        
        $status = array('message-content'=>'keyword 2');
        $this->assertFalse($DialogueHelper->hasNoMatchingAnswers($script, $status));
    }

    public function testGetInteraction()
    {
        $DialogueHelper = new DialogueHelper();
        $script = $this->getOneScript('keyword');
        $this->assertEqual(
            $script['Script']['script']['dialogues'][0]['interactions'][0],	
            $DialogueHelper->getInteraction($script, "script.dialogues[0].interactions[0]")
            );
        
        $this->assertEqual(
            $script['Script']['script']['dialogues'][0]['interactions'][1],	
            $DialogueHelper->getInteraction($script, "script.dialogues[0].interactions[1]")
            );
        
        $this->assertEqual(
            array(),	
            $DialogueHelper->getInteraction($script, "other")
            );
    }

    public function testGetRequestKeywordToValidate()
    {
        $DialogueHelper = new DialogueHelper();
        $requestKeywords = "www, www join, ww";
        $this->assertEqual('www, www, ww', 
            $DialogueHelper->getRequestKeywordToValidate($requestKeywords)
            );

    }

    public function testConvertDataFormat()
    {
        $DialogueHelper = new DialogueHelper();

        $vusionHtmlFormDate = "10/12/2012 10:12";
        $vusionHtmlSearchDate = "10/12/2012";
        $isoDate = "2012-12-10T10:12:00";
        
        $this->assertEqual('2012-12-10T10:12:00', $DialogueHelper->convertDateFormat($vusionHtmlFormDate));
        $this->assertEqual('2012-12-10T00:00:00', $DialogueHelper->convertDateFormat($vusionHtmlSearchDate));
        $this->assertEqual('2012-12-10T10:12:00', $DialogueHelper->convertDateFormat($isoDate));
 
       
    }

    
    
}