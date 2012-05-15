<?php
App::uses('MongoModel', 'Model');
App::uses('DialogueHelper', 'Lib');

class Dialogue extends MongoModel
{

    var $specific = true;
    var $name     = 'Dialogue';

    var $findMethods = array(
        'draft' => true,
        'first' => true,
        'count' => true,
        );

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->DialogueHelper = new DialogueHelper();
    }


    protected function _findDraft($state, $query, $results = array())
    {
        if ($state == 'before') {
            $query['conditions']['Dialogue.activated'] = 0;
            $query['conditions']['Dialogue.dialogue-id'] = $query['dialogue-id'];
            return $query;
        }
        return $results;
    }    


    public function beforeValidate()
    {

        if (!isset($this->data['Dialogue']['activated'])) {
            $this->data['Dialogue']['activated'] = 0;
        }

        if (!isset($this->data['Dialogue']['dialogue-id'])) {
            $this->data['Dialogue']['dialogue-id'] = uniqid();
        }   

        if (isset($this->data['Dialogue']['interactions'])) {
            foreach ($this->data['Dialogue']['interactions'] as &$interaction) {
                if (!isset($interaction['interaction-id']) || $interaction['interaction-id']=="")
                    $interaction['interaction-id'] = uniqid();
            }
        }

        $this->data['Dialogue'] = $this->DialogueHelper->objectToArray($this->data['Dialogue']);

        return $this->DialogueHelper->recurseScriptDateConverter($this->data['Dialogue']);
    }

    
    public function makeActive($objectId)
    {
        $this->id = $objectId;
        if (!$this->exists())
            return false;
        $dialogue = $this->read(null, $objectId);
        $dialogue['Dialogue']['activated'] = 1;
        return $this->save($dialogue);
    }


    public function makeDraftActive($dialogueId)
    {
        $draft = $this->find('draft', array('dialogue-id'=>$dialogueId));
        if ($draft) {
            $draft[0]['Dialogue']['activated'] = 1;
            $this->create();
            $this->id = $draft[0]['Dialogue']['_id'];
            $this->save($draft[0]['Dialogue']);
            return $draft[0];
        }
        return false;
    }


    public function getActiveDialogues()
    {
        $dialogueQuery = array(
            'key' => array(
                'dialogue-id' => true,
                ),
            'initial' => array('Dialogue' => 0),
            'reduce' => 'function(obj, prev){
                if (obj.activated && (prev.Dialogue==0 || prev.Dialogue.modified <= obj.modified)) 
                    prev.Dialogue = obj;
                }',
            );
        $dialogues = $this->getDataSource()->group($this, $dialogueQuery);
        return array_filter(
            $dialogues['retval'],
            array($this, "_filterDraft")
            );
    }

    public function getActiveInteractions()
    {
        $activeInteractions = array();
        $activeDialogues    = $this->getActiveDialogues();
        foreach ($activeDialogues as $activeDialogue) {
            $activeInteractions = array_merge($activeInteractions, $activeDialogue['Dialogue']['interactions']);
        }
        return $activeInteractions;
    }

    public function getDialogues()
    {
        $dialogueQuery = array(
            'key' => array(
                'dialogue-id' => true,
                ),
            'initial' => array('Dialogue' => 0),
            'reduce' => 'function(obj, prev){
                if (prev.Dialogue==0 || prev.Dialogue.modified <= obj.modified) 
                    prev.Dialogue = obj;
                }',
            );
        $dialogues = $this->getDataSource()->group($this, $dialogueQuery);
        return $dialogues['retval'];
    }

    public function getActiveAndDraft()
    {
        $dialogueQuery = array(
            'key' => array(
                'dialogue-id' => true,
                ),
            'initial' => array('Active' => 0, 'Draft' => 0),
            'reduce' => 'function(obj, prev){
                if (obj.activated && (!prev.Active || prev.Active.modified <= obj.modified)) 
                    prev.Active = obj;
                else if (!obj.activated)
                    prev.Draft = obj;
                }',
            );
        $dialogues = $this->getDataSource()->group($this, $dialogueQuery);
        return $dialogues['retval'];
    }


    protected function _filterDraft($dialogue)
    {
        return ($dialogue['Dialogue']!=0);
    }


    public function saveDialogue($dialogue)
    {
        $dialogue = $this->DialogueHelper->objectToArray($dialogue);

         if (!isset($dialogue['Dialogue']['dialogue-id'])) {
            $this->create();
            return $this->save($dialogue);
        }

        $draft = $this->find('draft', array('dialogue-id'=>$dialogue['Dialogue']['dialogue-id']) );
        $this->create();
        if ($draft) {
            $this->id                    = $draft[0]['Dialogue']['_id'];
            $dialogue['Dialogue']['_id'] = $draft[0]['Dialogue']['_id'];
        }
        return $this->save($dialogue);
    }

    public function useKeyword($keyword)
    {
        foreach ($this->getActiveDialogues() as $activeDialogue) {
            $foundKeyword = $this->DialogueHelper->hasKeyword($activeDialogue, $keyword);
            if ($foundKeyword) {
                return $foundKeyword;
            }
        }
        return array();
    }


    public function getActiveDialogueUseKeyword($keyword)
    {
        foreach ($this->getActiveDialogues() as $activeDialogue) {
            $foundKeyword = $this->DialogueHelper->hasKeyword($activeDialogue, $keyword);
            if ($foundKeyword) {
                return $activeDialogue;
            }
        }
        return array();
    }


}